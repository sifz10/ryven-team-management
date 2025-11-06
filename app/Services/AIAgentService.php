<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\GitHubLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AIAgentService
{
    private string $apiKey;
    private array $tools;
    private array $conversationHistory = [];

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->tools = $this->defineTools();
    }

    /**
     * Process a user command and return a response
     */
    public function processCommand(string $userMessage, ?int $userId = null): array
    {
        try {
            // Add user message to conversation history
            $this->conversationHistory[] = [
                'role' => 'user',
                'content' => $userMessage
            ];

            // Call OpenAI API with function calling
            $response = $this->callOpenAI();

            // Handle tool calls
            while (isset($response['choices'][0]['message']['tool_calls'])) {
                $message = $response['choices'][0]['message'];
                $this->conversationHistory[] = $message;

                // Execute each tool call
                foreach ($message['tool_calls'] as $toolCall) {
                    $result = $this->executeToolCall($toolCall);

                    $this->conversationHistory[] = [
                        'role' => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'content' => json_encode($result)
                    ];
                }

                // Get final response after tool execution
                $response = $this->callOpenAI();
            }

            $assistantMessage = $response['choices'][0]['message']['content'];
            $this->conversationHistory[] = [
                'role' => 'assistant',
                'content' => $assistantMessage
            ];

            return [
                'success' => true,
                'message' => $assistantMessage,
                'conversation_history' => $this->conversationHistory
            ];

        } catch (\Exception $e) {
            Log::error('AI Agent Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Sorry, I encountered an error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Call OpenAI API
     */
    private function callOpenAI(): array
    {
        $http = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ]);

        // Disable SSL verification for local development (Windows fix)
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => array_merge([
                [
                    'role' => 'system',
                    'content' => $this->getSystemPrompt()
                ]
            ], $this->conversationHistory),
            'tools' => $this->tools,
            'tool_choice' => 'auto',
            'temperature' => 0.7,
        ]);

        if (!$response->successful()) {
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Execute a tool call
     */
    private function executeToolCall(array $toolCall): array
    {
        $functionName = $toolCall['function']['name'];
        $arguments = json_decode($toolCall['function']['arguments'], true);

        return match($functionName) {
            'add_employee' => $this->addEmployee($arguments),
            'list_employees' => $this->listEmployees($arguments),
            'check_github_activity_today' => $this->checkGitHubActivityToday(),
            'find_inactive_developers_today' => $this->findInactiveDevelopersToday(),
            'get_employee_details' => $this->getEmployeeDetails($arguments),
            'search_employees' => $this->searchEmployees($arguments),
            'get_github_activity' => $this->getGitHubActivity($arguments),
            'get_employee_github_stats' => $this->getEmployeeGitHubStats($arguments),
            default => ['error' => 'Unknown function: ' . $functionName]
        };
    }

    /**
     * Define available tools/functions
     */
    private function defineTools(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'add_employee',
                    'description' => 'Add a new employee to the system',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'first_name' => [
                                'type' => 'string',
                                'description' => 'Employee first name'
                            ],
                            'last_name' => [
                                'type' => 'string',
                                'description' => 'Employee last name'
                            ],
                            'email' => [
                                'type' => 'string',
                                'description' => 'Employee email address'
                            ],
                            'github_username' => [
                                'type' => 'string',
                                'description' => 'GitHub username (optional)'
                            ],
                            'position' => [
                                'type' => 'string',
                                'description' => 'Job position'
                            ],
                            'department' => [
                                'type' => 'string',
                                'description' => 'Department'
                            ],
                            'salary' => [
                                'type' => 'number',
                                'description' => 'Salary amount'
                            ],
                            'currency' => [
                                'type' => 'string',
                                'description' => 'Currency code (BDT, USD, etc.)'
                            ],
                        ],
                        'required' => ['first_name', 'last_name', 'email']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'list_employees',
                    'description' => 'Get a list of all active employees',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'include_discontinued' => [
                                'type' => 'boolean',
                                'description' => 'Include discontinued employees'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'check_github_activity_today',
                    'description' => 'Check all GitHub activity for today across all employees',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => (object)[]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'find_inactive_developers_today',
                    'description' => 'Find employees who have not pushed any code today',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => (object)[]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_employee_details',
                    'description' => 'Get detailed information about a specific employee',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID'
                            ]
                        ],
                        'required' => ['employee_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'search_employees',
                    'description' => 'Search for employees by name, email, or GitHub username',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'query' => [
                                'type' => 'string',
                                'description' => 'Search query'
                            ]
                        ],
                        'required' => ['query']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_github_activity',
                    'description' => 'Get GitHub activity within a date range',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by employee ID (optional)'
                            ],
                            'days' => [
                                'type' => 'integer',
                                'description' => 'Number of days to look back (default: 7)'
                            ],
                            'event_type' => [
                                'type' => 'string',
                                'description' => 'Filter by event type: push, pull_request, pull_request_review'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_employee_github_stats',
                    'description' => 'Get GitHub statistics for a specific employee',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID'
                            ],
                            'days' => [
                                'type' => 'integer',
                                'description' => 'Number of days for statistics (default: 30)'
                            ]
                        ],
                        'required' => ['employee_id']
                    ]
                ]
            ]
        ];
    }

    /**
     * Get system prompt
     */
    private function getSystemPrompt(): string
    {
        return "You are an AI assistant for a team management system. You help manage employees, track GitHub activity, and provide insights about the team.

Your capabilities include:
- Adding new employees to the system
- Listing and searching employees
- Checking GitHub activity and finding who hasn't pushed code today
- Getting detailed employee information
- Providing GitHub statistics and reports

When users ask you to perform actions, use the appropriate tools to complete the task. Be helpful, concise, and professional. When reporting on inactive developers, be factual but not judgmental.

Today's date is " . now()->format('F j, Y') . ".";
    }

    // ==================== TOOL IMPLEMENTATIONS ====================

    private function addEmployee(array $data): array
    {
        try {
            $employee = Employee::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'github_username' => $data['github_username'] ?? null,
                'position' => $data['position'] ?? null,
                'department' => $data['department'] ?? null,
                'salary' => $data['salary'] ?? null,
                'currency' => $data['currency'] ?? 'BDT',
                'hired_at' => now(),
            ]);

            return [
                'success' => true,
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'email' => $employee->email,
                    'github_username' => $employee->github_username,
                    'position' => $employee->position,
                    'department' => $employee->department,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function listEmployees(array $data): array
    {
        $query = Employee::query();

        if (empty($data['include_discontinued'])) {
            $query->whereNull('discontinued_at');
        }

        $employees = $query->orderBy('first_name')->get()->map(function($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'email' => $emp->email,
                'github_username' => $emp->github_username,
                'position' => $emp->position,
                'department' => $emp->department,
                'hired_at' => $emp->hired_at?->format('Y-m-d'),
                'is_active' => is_null($emp->discontinued_at)
            ];
        });

        return [
            'count' => $employees->count(),
            'employees' => $employees->toArray()
        ];
    }

    private function checkGitHubActivityToday(): array
    {
        $today = Carbon::today();

        $activities = GitHubLog::whereDate('event_at', $today)
            ->with('employee')
            ->orderBy('event_at', 'desc')
            ->get()
            ->map(function($log) {
                return [
                    'employee_name' => $log->employee ?
                        $log->employee->first_name . ' ' . $log->employee->last_name :
                        $log->author_username,
                    'employee_id' => $log->employee_id,
                    'event_type' => $log->event_type,
                    'action' => $log->action,
                    'repository' => $log->repository_name,
                    'time' => $log->event_at->format('H:i:s'),
                    'details' => $log->commit_message ?? $log->pr_title ?? null
                ];
            });

        return [
            'date' => $today->format('Y-m-d'),
            'total_activities' => $activities->count(),
            'activities' => $activities->toArray()
        ];
    }

    private function findInactiveDevelopersToday(): array
    {
        $today = Carbon::today();

        // Get all active employees with GitHub usernames
        $allEmployees = Employee::whereNull('discontinued_at')
            ->whereNotNull('github_username')
            ->get();

        // Get employees who pushed today
        $activeToday = GitHubLog::whereDate('event_at', $today)
            ->where('event_type', 'push')
            ->distinct('employee_id')
            ->pluck('employee_id')
            ->toArray();

        // Find inactive ones
        $inactive = $allEmployees->filter(function($emp) use ($activeToday) {
            return !in_array($emp->id, $activeToday);
        })->map(function($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'email' => $emp->email,
                'github_username' => $emp->github_username,
                'position' => $emp->position,
                'department' => $emp->department
            ];
        });

        return [
            'date' => $today->format('Y-m-d'),
            'total_employees_with_github' => $allEmployees->count(),
            'active_today' => count($activeToday),
            'inactive_today' => $inactive->count(),
            'inactive_employees' => $inactive->values()->toArray()
        ];
    }

    private function getEmployeeDetails(array $data): array
    {
        $employee = Employee::find($data['employee_id']);

        if (!$employee) {
            return ['error' => 'Employee not found'];
        }

        return [
            'id' => $employee->id,
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'email' => $employee->email,
            'github_username' => $employee->github_username,
            'phone' => $employee->phone,
            'position' => $employee->position,
            'department' => $employee->department,
            'salary' => $employee->salary,
            'currency' => $employee->currency,
            'hired_at' => $employee->hired_at?->format('Y-m-d'),
            'discontinued_at' => $employee->discontinued_at?->format('Y-m-d'),
            'is_active' => is_null($employee->discontinued_at)
        ];
    }

    private function searchEmployees(array $data): array
    {
        $query = $data['query'];

        $employees = Employee::where(function($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('github_username', 'like', "%{$query}%");
        })
        ->whereNull('discontinued_at')
        ->get()
        ->map(function($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'email' => $emp->email,
                'github_username' => $emp->github_username,
                'position' => $emp->position,
                'department' => $emp->department
            ];
        });

        return [
            'query' => $query,
            'count' => $employees->count(),
            'employees' => $employees->toArray()
        ];
    }

    private function getGitHubActivity(array $data): array
    {
        $days = $data['days'] ?? 7;
        $startDate = Carbon::now()->subDays($days);

        $query = GitHubLog::where('event_at', '>=', $startDate)
            ->with('employee');

        if (isset($data['employee_id'])) {
            $query->where('employee_id', $data['employee_id']);
        }

        if (isset($data['event_type'])) {
            $query->where('event_type', $data['event_type']);
        }

        $activities = $query->orderBy('event_at', 'desc')
            ->get()
            ->map(function($log) {
                return [
                    'employee_name' => $log->employee ?
                        $log->employee->first_name . ' ' . $log->employee->last_name :
                        $log->author_username,
                    'event_type' => $log->event_type,
                    'action' => $log->action,
                    'repository' => $log->repository_name,
                    'date' => $log->event_at->format('Y-m-d H:i:s'),
                    'details' => $log->commit_message ?? $log->pr_title ?? null
                ];
            });

        return [
            'period' => $days . ' days',
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'total_activities' => $activities->count(),
            'activities' => $activities->toArray()
        ];
    }

    private function getEmployeeGitHubStats(array $data): array
    {
        $days = $data['days'] ?? 30;
        $startDate = Carbon::now()->subDays($days);

        $employee = Employee::find($data['employee_id']);
        if (!$employee) {
            return ['error' => 'Employee not found'];
        }

        $activities = GitHubLog::where('employee_id', $data['employee_id'])
            ->where('event_at', '>=', $startDate)
            ->get();

        $pushCount = $activities->where('event_type', 'push')->count();
        $prCount = $activities->where('event_type', 'pull_request')->count();
        $reviewCount = $activities->where('event_type', 'pull_request_review')->count();
        $totalCommits = $activities->where('event_type', 'push')->sum('commits_count');

        return [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'github_username' => $employee->github_username
            ],
            'period' => $days . ' days',
            'statistics' => [
                'total_pushes' => $pushCount,
                'total_commits' => $totalCommits,
                'pull_requests_created' => $prCount,
                'pull_request_reviews' => $reviewCount,
                'total_activities' => $activities->count(),
                'average_commits_per_push' => $pushCount > 0 ? round($totalCommits / $pushCount, 2) : 0
            ],
            'repositories_worked_on' => $activities->pluck('repository_name')->unique()->values()->toArray()
        ];
    }
}
