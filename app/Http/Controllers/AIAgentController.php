<?php

namespace App\Http\Controllers;

use App\Services\AIAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIAgentController extends Controller
{
    private AIAgentService $agentService;

    public function __construct(AIAgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    /**
     * Show the AI Agent dashboard
     */
    public function index()
    {
        return view('ai-agent.index');
    }

    /**
     * Process a command from the user
     */
    public function processCommand(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userId = Auth::id();
        $result = $this->agentService->processCommand($request->message, $userId);

        return response()->json($result);
    }

    /**
     * Get conversation history
     */
    public function getConversationHistory(Request $request)
    {
        // In a production system, you'd store conversation history per user
        // For now, returning empty as each request is stateless
        return response()->json([
            'conversation' => []
        ]);
    }

    /**
     * Clear conversation history
     */
    public function clearConversation(Request $request)
    {
        // Clear user's conversation from storage
        return response()->json([
            'success' => true,
            'message' => 'Conversation cleared'
        ]);
    }
}
