<?php

namespace App\Services;

use App\Models\ProjectTicket;
use App\Models\TicketComment;
use App\Models\Employee;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketAIService
{
    private string $apiKey;
    private string $model = 'gpt-4o-mini';
    private int $maxTokens = 800;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Generate AI response to ticket or comment
     */
    public function generateResponse(ProjectTicket $ticket, ?string $latestUserMessage = null): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API key not configured');
            return null;
        }

        try {
            // Build conversation context
            $messages = $this->buildConversationContext($ticket, $latestUserMessage);

            // Call OpenAI API with SSL verification disabled for local development
            $httpClient = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30);

            // Disable SSL verification in local environment (Windows dev workaround)
            if (app()->environment('local')) {
                $httpClient = $httpClient->withoutVerifying();
            }

            $response = $httpClient->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => $this->maxTokens,
            ]);

            if (!$response->successful()) {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            $aiMessage = $data['choices'][0]['message']['content'] ?? null;

            if (!$aiMessage) {
                return null;
            }

            // Parse AI response for actions
            $actions = $this->parseAIActions($aiMessage);

            return [
                'message' => $this->cleanAIMessage($aiMessage),
                'actions' => $actions,
                'should_escalate' => $actions['escalate'] ?? false,
                'suggested_priority' => $actions['priority'] ?? null,
                'suggested_status' => $actions['status'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('AI response generation failed', [
                'error' => $e->getMessage(),
                'ticket_id' => $ticket->id,
            ]);
            return null;
        }
    }

    /**
     * Build conversation context for AI
     */
    private function buildConversationContext(ProjectTicket $ticket, ?string $latestUserMessage): array
    {
        $ticket->load(['project', 'reportedBy', 'comments.employee']);

        // System prompt
        $systemPrompt = $this->getSystemPrompt($ticket);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add ticket initial description as user message
        $messages[] = [
            'role' => 'user',
            'content' => "**New Ticket Created**\n\nTitle: {$ticket->title}\n\nDescription: {$ticket->description}",
        ];

        // Add conversation history
        $comments = $ticket->comments()
            ->with('employee')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($comments as $comment) {
            $isAI = $comment->employee_id === null && str_contains($comment->comment, '[AI Assistant]');

            $messages[] = [
                'role' => $isAI ? 'assistant' : 'user',
                'content' => $isAI
                    ? str_replace('[AI Assistant] ', '', $comment->comment)
                    : $comment->comment,
            ];
        }

        // Add latest user message if provided
        if ($latestUserMessage) {
            $messages[] = [
                'role' => 'user',
                'content' => $latestUserMessage,
            ];
        }

        return $messages;
    }

    /**
     * Get system prompt for AI assistant
     */
    private function getSystemPrompt(ProjectTicket $ticket): string
    {
        $projectName = $ticket->project->name ?? 'Unknown Project';
        $ticketType = ucfirst($ticket->type);
        $currentPriority = ucfirst($ticket->priority);

        return <<<PROMPT
You are a helpful and empathetic technical support AI assistant for {$projectName}. Your role is to gather information from clients about their issues before escalating to the human support team.

**Current Ticket Context:**
- Type: {$ticketType}
- Priority: {$currentPriority}
- Project: {$projectName}

**Your Objectives:**
1. **Gather Complete Information**: Ask clarifying questions to fully understand the problem
2. **Be Empathetic**: Show understanding and patience with the client's frustration
3. **Troubleshoot When Possible**: If you can suggest simple solutions, do so
4. **Know When to Escalate**: If the issue requires human intervention, clearly indicate it
5. **Assess Urgency**: Determine if priority should be adjusted based on client responses

**Communication Style:**
- Write like a real human, not a robot - be conversational and friendly
- Use natural language, contractions, and empathy ("I understand how frustrating this must be")
- Keep responses concise (2-4 sentences typically)
- Ask ONE specific question at a time
- Avoid technical jargon unless the client uses it first
- Never mention that you're an AI or provide code snippets

**Information to Gather:**
- What exactly happened? (step-by-step if possible)
- When did this start occurring?
- Is this affecting multiple users or just them?
- Have they tried any solutions already?
- What's the impact on their work/business?
- Can they provide screenshots or error messages?

**When to Escalate:**
- Complex technical issues requiring code changes
- Security or data concerns
- Issues affecting multiple users/critical systems
- Client explicitly requests human support
- You've asked 4-5 questions and still need more info
- Issue requires access to systems you can't see

**Action Commands** (use these in your response when needed):
- [ESCALATE] - Mark ticket ready for human team member
- [PRIORITY:critical] - Suggest changing priority to critical
- [PRIORITY:high] - Suggest changing priority to high
- [PRIORITY:medium] - Suggest changing priority to medium
- [PRIORITY:low] - Suggest changing priority to low
- [STATUS:in-progress] - Mark as actively being worked on
- [STATUS:resolved] - Mark as resolved (only if client confirms fix worked)

**Example Good Response:**
"Oh no, I'm sorry to hear the login isn't working! That must be really frustrating. Can you tell me what happens when you try to log in? Do you see an error message, or does it just not respond when you click the login button?"

**Example Escalation Response:**
"Thanks for providing all that detail - this helps a lot! Based on what you've described, this looks like it needs our development team to investigate the database connection. I'm escalating this to a senior team member who will reach out to you within the next few hours. [ESCALATE] [PRIORITY:high]"

Now, respond to the client's message naturally and helpfully.
PROMPT;
    }

    /**
     * Parse action commands from AI response
     */
    private function parseAIActions(string $message): array
    {
        $actions = [
            'escalate' => false,
            'priority' => null,
            'status' => null,
        ];

        // Check for escalation
        if (preg_match('/\[ESCALATE\]/i', $message)) {
            $actions['escalate'] = true;
        }

        // Check for priority change
        if (preg_match('/\[PRIORITY:(critical|high|medium|low)\]/i', $message, $matches)) {
            $actions['priority'] = strtolower($matches[1]);
        }

        // Check for status change
        if (preg_match('/\[STATUS:(open|in-progress|resolved|closed)\]/i', $message, $matches)) {
            $actions['status'] = strtolower($matches[1]);
        }

        return $actions;
    }

    /**
     * Clean AI message by removing action commands
     */
    private function cleanAIMessage(string $message): string
    {
        // Remove action commands
        $message = preg_replace('/\[ESCALATE\]/i', '', $message);
        $message = preg_replace('/\[PRIORITY:\w+\]/i', '', $message);
        $message = preg_replace('/\[STATUS:\w+\]/i', '', $message);

        return trim($message);
    }

    /**
     * Check if AI should respond to this comment
     */
    public function shouldAIRespond(ProjectTicket $ticket, TicketComment $newComment): bool
    {
        // Don't respond to AI's own comments (user_id is null for AI)
        if ($newComment->user_id === null) {
            return false;
        }

        // Don't respond if ticket is closed
        if (in_array($ticket->status, ['closed', 'resolved'])) {
            return false;
        }

        // TEMPORARILY DISABLED: Allow AI to respond even if assigned (for testing)
        // Uncomment this when ready for production:
        // if ($ticket->assigned_to !== null) {
        //     return false;
        // }

        // Check if AI has already escalated (look for [ESCALATE] in previous comments)
        $hasEscalated = $ticket->comments()
            ->where('user_id', null) // AI comments have null user_id
            ->where('comment', 'like', '%[ESCALATE]%')
            ->exists();

        if ($hasEscalated) {
            return false;
        }

        // Respond to all other client messages
        return true;
    }

    /**
     * Get AI assistant employee record (create if doesn't exist)
     */
    public function getAIAssistantEmployee(): ?Employee
    {
        // For now, we'll use null employee_id for AI comments
        // This way we can identify them easily
        return null;
    }
}
