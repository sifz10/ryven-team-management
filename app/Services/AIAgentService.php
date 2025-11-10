<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\GitHubLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

// Job Management Service
use App\Services\AIJobManagementService;

class AIAgentService
{
    private string $apiKey;
    private array $tools;
    private array $conversationHistory = [];
    private AIJobManagementService $jobService;

    public function __construct(AIJobManagementService $jobService)
    {
        $this->apiKey = config('services.openai.api_key');
        $this->tools = $this->defineTools();
        $this->jobService = $jobService;
    }

    /**
     * Process a user command and return a response
     */
    public function processCommand(string $userMessage, ?int $userId = null, array $previousHistory = []): array
    {
        try {
            // Load previous conversation history if provided
            if (!empty($previousHistory)) {
                $this->conversationHistory = $previousHistory;
            }

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
            'get_attendance_data' => $this->getAttendanceData($arguments),
            'get_projects_data' => $this->getProjectsData($arguments),
            'get_uat_data' => $this->getUatData($arguments),
            'get_invoices_data' => $this->getInvoicesData($arguments),
            'get_contracts_data' => $this->getContractsData($arguments),
            'get_performance_reviews' => $this->getPerformanceReviews($arguments),
            'get_personal_notes' => $this->getPersonalNotes($arguments),
            'create_personal_note' => $this->createPersonalNote($arguments),
            'get_platform_statistics' => $this->getPlatformStatistics(),
            'search_platform_data' => $this->searchPlatformData($arguments),
            'create_checklist' => $this->createChecklist($arguments),
            'get_checklists' => $this->getChecklists($arguments),
            'update_checklist' => $this->updateChecklist($arguments),
            'delete_checklist' => $this->deleteChecklist($arguments),
            'send_checklist_email' => $this->sendChecklistEmail($arguments),
            'generate_email' => $this->generateEmail($arguments),
            'send_custom_email' => $this->sendCustomEmail($arguments),
            'get_employee_profile' => $this->getEmployeeProfile($arguments),
            'update_employee_profile' => $this->updateEmployeeProfile($arguments),
            'add_activity_log' => $this->addActivityLog($arguments),
            'get_employee_activity_logs' => $this->getEmployeeActivityLogs($arguments),
            'manage_employee_access' => $this->manageEmployeeAccess($arguments),
            'get_employee_github_activity' => $this->getEmployeeGitHubActivity($arguments),
            // Job Management Functions
            'get_job_analytics' => $this->getJobAnalytics($arguments),
            'list_job_posts' => $this->listJobPosts($arguments),
            'create_job_post' => $this->createJobPost($arguments),
            'update_job_post' => $this->updateJobPost($arguments),
            'delete_job_post' => $this->deleteJobPost($arguments),
            'search_applications' => $this->searchApplications($arguments),
            'get_application_details' => $this->getApplicationDetails($arguments),
            'update_application_status' => $this->updateApplicationStatus($arguments),
            'delete_application' => $this->deleteApplication($arguments),
            'add_to_talent_pool' => $this->addToTalentPool($arguments),
            'get_talent_pool' => $this->getTalentPool($arguments),
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
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_attendance_data',
                    'description' => 'Get attendance records for employees with optional filters',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by employee ID (optional)'
                            ],
                            'month' => [
                                'type' => 'string',
                                'description' => 'Filter by month (YYYY-MM format, optional)'
                            ],
                            'limit' => [
                                'type' => 'integer',
                                'description' => 'Limit number of results (default: 50)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_projects_data',
                    'description' => 'Get all projects or specific project details',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'project_id' => [
                                'type' => 'integer',
                                'description' => 'Get specific project by ID (optional)'
                            ],
                            'status' => [
                                'type' => 'string',
                                'description' => 'Filter by status: active, completed, on_hold (optional)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_uat_data',
                    'description' => 'Get UAT (User Acceptance Testing) projects and test cases',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'project_id' => [
                                'type' => 'integer',
                                'description' => 'Get specific UAT project (optional)'
                            ],
                            'include_test_cases' => [
                                'type' => 'boolean',
                                'description' => 'Include test cases details (default: true)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_invoices_data',
                    'description' => 'Get invoice information with optional filters',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'status' => [
                                'type' => 'string',
                                'description' => 'Filter by status: paid, unpaid, overdue (optional)'
                            ],
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by employee ID (optional)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_contracts_data',
                    'description' => 'Get employment contract information',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by employee ID (optional)'
                            ],
                            'active_only' => [
                                'type' => 'boolean',
                                'description' => 'Show only active contracts (default: true)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_performance_reviews',
                    'description' => 'Get performance review data',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by employee ID (optional)'
                            ],
                            'cycle_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by review cycle ID (optional)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_personal_notes',
                    'description' => 'Get personal notes and saved information',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'description' => 'Filter by type: text, password, backup_code, website_link, file (optional)'
                            ],
                            'search' => [
                                'type' => 'string',
                                'description' => 'Search in title or content (optional)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'create_personal_note',
                    'description' => 'Create a new personal note for the user. Use this when the user wants to save information, take a note, remember something, save a password, backup code, website link, or any personal information.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => [
                                'type' => 'string',
                                'description' => 'Note title or name'
                            ],
                            'content' => [
                                'type' => 'string',
                                'description' => 'Note content, text, password, or information (optional for website_link type)'
                            ],
                            'type' => [
                                'type' => 'string',
                                'enum' => ['text', 'password', 'backup_code', 'website_link', 'file'],
                                'description' => 'Type of note: text (default), password, backup_code, website_link, file'
                            ],
                            'url' => [
                                'type' => 'string',
                                'description' => 'URL for website_link type notes (optional)'
                            ],
                            'reminder_time' => [
                                'type' => 'string',
                                'description' => 'Optional reminder date/time in format YYYY-MM-DD HH:MM:SS (optional)'
                            ]
                        ],
                        'required' => ['title', 'type']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_platform_statistics',
                    'description' => 'Get overall platform statistics and metrics',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => (object)[]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'search_platform_data',
                    'description' => 'Search across all platform data (employees, projects, UAT, etc.)',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'query' => [
                                'type' => 'string',
                                'description' => 'Search query'
                            ],
                            'categories' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'description' => 'Categories to search: employees, projects, uat, invoices, contracts (optional)'
                            ]
                        ],
                        'required' => ['query']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'create_checklist',
                    'description' => 'Create a new checklist template or daily checklist for an employee',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID to assign the checklist to'
                            ],
                            'title' => [
                                'type' => 'string',
                                'description' => 'Checklist title'
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'Checklist description (optional)'
                            ],
                            'items' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'description' => 'List of checklist items/tasks'
                            ],
                            'type' => [
                                'type' => 'string',
                                'enum' => ['template', 'daily'],
                                'description' => 'Type: template (reusable) or daily (one-time)'
                            ]
                        ],
                        'required' => ['employee_id', 'title', 'items', 'type']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_checklists',
                    'description' => 'Get checklists (templates or daily checklists)',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by employee ID (optional)'
                            ],
                            'type' => [
                                'type' => 'string',
                                'enum' => ['template', 'daily'],
                                'description' => 'Filter by type (optional)'
                            ],
                            'date' => [
                                'type' => 'string',
                                'description' => 'Filter daily checklists by date YYYY-MM-DD (optional)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'update_checklist',
                    'description' => 'Update an existing checklist template or daily checklist',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'checklist_id' => [
                                'type' => 'integer',
                                'description' => 'Checklist ID to update'
                            ],
                            'checklist_type' => [
                                'type' => 'string',
                                'enum' => ['template', 'daily'],
                                'description' => 'Type of checklist'
                            ],
                            'title' => [
                                'type' => 'string',
                                'description' => 'New title (optional)'
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'New description (optional)'
                            ],
                            'items' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'description' => 'Updated list of items (optional)'
                            ]
                        ],
                        'required' => ['checklist_id', 'checklist_type']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'delete_checklist',
                    'description' => 'Delete a checklist template or daily checklist',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'checklist_id' => [
                                'type' => 'integer',
                                'description' => 'Checklist ID to delete'
                            ],
                            'checklist_type' => [
                                'type' => 'string',
                                'enum' => ['template', 'daily'],
                                'description' => 'Type of checklist'
                            ]
                        ],
                        'required' => ['checklist_id', 'checklist_type']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'send_checklist_email',
                    'description' => 'Send a checklist to an employee via email',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'checklist_id' => [
                                'type' => 'integer',
                                'description' => 'Daily checklist ID to send'
                            ],
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID to send to'
                            ]
                        ],
                        'required' => ['checklist_id', 'employee_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'generate_email',
                    'description' => 'Generate an email draft with AI assistance. Use this when user wants to send an email but hasn\'t provided the content yet.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'recipient_email' => [
                                'type' => 'string',
                                'description' => 'Recipient email address'
                            ],
                            'recipient_name' => [
                                'type' => 'string',
                                'description' => 'Recipient name (optional)'
                            ],
                            'subject' => [
                                'type' => 'string',
                                'description' => 'Email subject'
                            ],
                            'purpose' => [
                                'type' => 'string',
                                'description' => 'Purpose or context of the email (e.g., "meeting reminder", "project update")'
                            ],
                            'key_points' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'description' => 'Key points to include in the email (optional)'
                            ],
                            'tone' => [
                                'type' => 'string',
                                'enum' => ['formal', 'casual', 'friendly', 'professional'],
                                'description' => 'Email tone (optional, default: professional)'
                            ]
                        ],
                        'required' => ['recipient_email', 'subject', 'purpose']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'send_custom_email',
                    'description' => 'Send a custom email to an employee or any email address',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'recipient_email' => [
                                'type' => 'string',
                                'description' => 'Recipient email address'
                            ],
                            'recipient_name' => [
                                'type' => 'string',
                                'description' => 'Recipient name (optional)'
                            ],
                            'subject' => [
                                'type' => 'string',
                                'description' => 'Email subject'
                            ],
                            'body' => [
                                'type' => 'string',
                                'description' => 'Email body content (HTML or plain text)'
                            ]
                        ],
                        'required' => ['recipient_email', 'subject', 'body']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_employee_profile',
                    'description' => 'Get comprehensive employee profile including all related data: contracts, checklists, GitHub activity, attendance, payments, etc.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID'
                            ],
                            'include' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'description' => 'Data to include: contracts, checklists, github, attendance, payments, access, all (default: all)'
                            ]
                        ],
                        'required' => ['employee_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'update_employee_profile',
                    'description' => 'Update employee profile information (name, email, position, department, salary, etc.)',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID'
                            ],
                            'first_name' => [
                                'type' => 'string',
                                'description' => 'First name (optional)'
                            ],
                            'last_name' => [
                                'type' => 'string',
                                'description' => 'Last name (optional)'
                            ],
                            'email' => [
                                'type' => 'string',
                                'description' => 'Email address (optional)'
                            ],
                            'github_username' => [
                                'type' => 'string',
                                'description' => 'GitHub username (optional)'
                            ],
                            'phone' => [
                                'type' => 'string',
                                'description' => 'Phone number (optional)'
                            ],
                            'position' => [
                                'type' => 'string',
                                'description' => 'Job position (optional)'
                            ],
                            'department' => [
                                'type' => 'string',
                                'description' => 'Department (optional)'
                            ],
                            'salary' => [
                                'type' => 'number',
                                'description' => 'Salary amount (optional)'
                            ],
                            'currency' => [
                                'type' => 'string',
                                'description' => 'Currency (USD, BDT, etc.) (optional)'
                            ]
                        ],
                        'required' => ['employee_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'add_activity_log',
                    'description' => 'Add an activity note/log for an employee payment or action',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_payment_id' => [
                                'type' => 'integer',
                                'description' => 'Employee payment ID (optional, if logging payment activity)'
                            ],
                            'note' => [
                                'type' => 'string',
                                'description' => 'Activity note or log message'
                            ]
                        ],
                        'required' => ['note']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_employee_activity_logs',
                    'description' => 'Get activity logs for an employee or payment',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_payment_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by payment ID (optional)'
                            ],
                            'limit' => [
                                'type' => 'integer',
                                'description' => 'Number of logs to retrieve (default: 50)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'manage_employee_access',
                    'description' => 'Add or update employee access credentials (servers, tools, accounts, etc.)',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID'
                            ],
                            'action' => [
                                'type' => 'string',
                                'enum' => ['add', 'list'],
                                'description' => 'Action: add new access or list existing'
                            ],
                            'title' => [
                                'type' => 'string',
                                'description' => 'Access title/name (required for add)'
                            ],
                            'note_markdown' => [
                                'type' => 'string',
                                'description' => 'Access details in markdown (credentials, URLs, etc.) (optional)'
                            ]
                        ],
                        'required' => ['employee_id', 'action']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_employee_github_activity',
                    'description' => 'Get detailed GitHub activity for a specific employee',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'employee_id' => [
                                'type' => 'integer',
                                'description' => 'Employee ID'
                            ],
                            'days' => [
                                'type' => 'integer',
                                'description' => 'Number of days to look back (default: 30)'
                            ],
                            'event_type' => [
                                'type' => 'string',
                                'enum' => ['push', 'pull_request', 'pull_request_review', 'all'],
                                'description' => 'Filter by event type (optional, default: all)'
                            ]
                        ],
                        'required' => ['employee_id']
                    ]
                ]
            ],
            // Job Management Tools
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_job_analytics',
                    'description' => 'Get comprehensive analytics and statistics for job postings and applications',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'job_post_id' => [
                                'type' => 'integer',
                                'description' => 'Get analytics for specific job post (optional, omit for all jobs)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'list_job_posts',
                    'description' => 'Get all job postings with details and application counts',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'status' => [
                                'type' => 'string',
                                'enum' => ['draft', 'active', 'closed'],
                                'description' => 'Filter by job status (optional)'
                            ],
                            'department' => [
                                'type' => 'string',
                                'description' => 'Filter by department (optional)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'create_job_post',
                    'description' => 'Create a new job posting',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => [
                                'type' => 'string',
                                'description' => 'Job title'
                            ],
                            'department' => [
                                'type' => 'string',
                                'description' => 'Department name (optional)'
                            ],
                            'location' => [
                                'type' => 'string',
                                'description' => 'Job location (optional)'
                            ],
                            'job_type' => [
                                'type' => 'string',
                                'enum' => ['full-time', 'part-time', 'contract', 'internship'],
                                'description' => 'Job type (optional, default: full-time)'
                            ],
                            'experience_level' => [
                                'type' => 'string',
                                'enum' => ['entry', 'junior', 'mid', 'senior', 'lead', 'executive'],
                                'description' => 'Required experience level (optional)'
                            ],
                            'salary_range' => [
                                'type' => 'string',
                                'description' => 'Salary range (e.g., "$50,000 - $70,000") (optional)'
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'Job description'
                            ],
                            'requirements' => [
                                'type' => 'string',
                                'description' => 'Job requirements (optional)'
                            ],
                            'responsibilities' => [
                                'type' => 'string',
                                'description' => 'Job responsibilities (optional)'
                            ],
                            'benefits' => [
                                'type' => 'string',
                                'description' => 'Benefits offered (optional)'
                            ],
                            'status' => [
                                'type' => 'string',
                                'enum' => ['draft', 'active', 'closed'],
                                'description' => 'Initial status (optional, default: draft)'
                            ],
                            'deadline' => [
                                'type' => 'string',
                                'description' => 'Application deadline (YYYY-MM-DD format) (optional)'
                            ]
                        ],
                        'required' => ['title', 'description']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'update_job_post',
                    'description' => 'Update an existing job posting',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'job_post_id' => [
                                'type' => 'integer',
                                'description' => 'Job post ID to update'
                            ],
                            'title' => [
                                'type' => 'string',
                                'description' => 'New job title (optional)'
                            ],
                            'status' => [
                                'type' => 'string',
                                'enum' => ['draft', 'active', 'closed'],
                                'description' => 'Update status (optional)'
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'Updated description (optional)'
                            ],
                            'deadline' => [
                                'type' => 'string',
                                'description' => 'New deadline (YYYY-MM-DD) (optional)'
                            ]
                        ],
                        'required' => ['job_post_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'delete_job_post',
                    'description' => 'Delete a job posting and all its applications',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'job_post_id' => [
                                'type' => 'integer',
                                'description' => 'Job post ID to delete'
                            ]
                        ],
                        'required' => ['job_post_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'search_applications',
                    'description' => 'Search and filter job applications',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'job_post_id' => [
                                'type' => 'integer',
                                'description' => 'Filter by job post ID (optional)'
                            ],
                            'status' => [
                                'type' => 'string',
                                'enum' => ['pending', 'reviewing', 'shortlisted', 'interview', 'offer', 'rejected', 'hired'],
                                'description' => 'Filter by application status (optional)'
                            ],
                            'ai_status' => [
                                'type' => 'string',
                                'enum' => ['pending', 'best_match', 'good_to_go', 'not_good_fit'],
                                'description' => 'Filter by AI screening status (optional)'
                            ],
                            'search' => [
                                'type' => 'string',
                                'description' => 'Search by name or email (optional)'
                            ],
                            'min_experience' => [
                                'type' => 'integer',
                                'description' => 'Minimum years of experience (optional)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_application_details',
                    'description' => 'Get detailed information about a specific job application including AI analysis, resume, answers, and tests',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'application_id' => [
                                'type' => 'integer',
                                'description' => 'Application ID'
                            ]
                        ],
                        'required' => ['application_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'update_application_status',
                    'description' => 'Update the status of a job application (e.g., move to interview, reject, hire)',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'application_id' => [
                                'type' => 'integer',
                                'description' => 'Application ID'
                            ],
                            'status' => [
                                'type' => 'string',
                                'enum' => ['pending', 'reviewing', 'shortlisted', 'interview', 'offer', 'rejected', 'hired'],
                                'description' => 'New status'
                            ],
                            'notes' => [
                                'type' => 'string',
                                'description' => 'Admin notes about the status change (optional)'
                            ]
                        ],
                        'required' => ['application_id', 'status']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'delete_application',
                    'description' => 'Delete a job application and all related data',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'application_id' => [
                                'type' => 'integer',
                                'description' => 'Application ID to delete'
                            ]
                        ],
                        'required' => ['application_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'add_to_talent_pool',
                    'description' => 'Add a candidate to the talent pool for future opportunities',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'application_id' => [
                                'type' => 'integer',
                                'description' => 'Application ID to add to talent pool'
                            ],
                            'skills' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'description' => 'Candidate skills (optional)'
                            ],
                            'experience_level' => [
                                'type' => 'string',
                                'enum' => ['entry', 'junior', 'mid', 'senior', 'lead', 'executive'],
                                'description' => 'Experience level (optional)'
                            ],
                            'status' => [
                                'type' => 'string',
                                'enum' => ['potential', 'contacted', 'interested', 'hired'],
                                'description' => 'Talent pool status (optional, default: potential)'
                            ],
                            'notes' => [
                                'type' => 'string',
                                'description' => 'Notes about the candidate (optional)'
                            ]
                        ],
                        'required' => ['application_id']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_talent_pool',
                    'description' => 'Get candidates from the talent pool with optional filters',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'status' => [
                                'type' => 'string',
                                'enum' => ['potential', 'contacted', 'interested', 'hired'],
                                'description' => 'Filter by status (optional)'
                            ],
                            'experience_level' => [
                                'type' => 'string',
                                'enum' => ['entry', 'junior', 'mid', 'senior', 'lead', 'executive'],
                                'description' => 'Filter by experience level (optional)'
                            ],
                            'search' => [
                                'type' => 'string',
                                'description' => 'Search by name or email (optional)'
                            ]
                        ]
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
        return "You are an AI assistant for a comprehensive team management platform. You have access to all platform data and can help users with various tasks.

Your capabilities include:

**Employee Management:**
- Add, list, and search employees
- Get comprehensive employee profiles with all related data
- Update employee information (name, email, position, salary, etc.)
- Track employee contracts and discontinuation status
- Manage employee access credentials (servers, tools, accounts)
- Add and view activity logs for employees

**GitHub & Development:**
- Check GitHub activity (commits, PRs, reviews)
- Find inactive developers and generate activity reports
- Analyze employee GitHub statistics and contributions
- Get detailed GitHub activity for specific employees
- Track repositories, commits, PRs, and reviews per employee

**Attendance Tracking:**
- View attendance records with filters (by employee, month)
- Track check-in/check-out times and attendance status

**Project Management:**
- List and search projects
- View project details, status, and client information
- Track active, completed, and pending projects

**UAT Testing:**
- Access UAT projects and test cases
- View test status, priorities, and feedback
- Track testing progress and completion

**Financial & Invoices:**
- View invoice status (paid, unpaid, pending)
- Filter invoices by employee or status
- Track total amounts and payment information

**Employment Contracts:**
- Review employment contracts and details
- View active contracts by employee
- Track salary, position, and contract dates

**Performance Reviews:**
- Access performance review data
- View ratings, feedback, and review cycles
- Filter reviews by employee or cycle

**Personal Notes:**
- Create and save personal notes (text, passwords, backup codes, website links)
- Search and manage personal notes (user's own notes only)
- Filter by type, category, or search term
- Track notes with reminders
- Support for different note types: text, password, backup_code, website_link, file

**Checklist Management:**
- Create checklist templates (reusable) or daily checklists (one-time)
- View, update, and delete checklists
- Send checklists to employees via email
- Track checklist completion and progress

**Email Communication:**
- Generate professional emails with AI assistance
- Send custom emails to employees or any email address
- Interactive email creation workflow: ask recipient, subject, generate draft, get approval, and send
- Support for different tones: formal, casual, friendly, professional

**Job Posting & Recruitment (NEW):**
- Create, view, update, and delete job postings
- Manage job requirements, descriptions, and screening questions
- Track application statistics and analytics (total applications, best matches, interviews, hires)
- Search and filter job applications by status, AI screening results, experience level
- View detailed application information including resume, AI analysis, screening answers, and tests
- Update application status (pending, reviewing, shortlisted, interview, offer, rejected, hired)
- Delete applications with all related files and data
- Add candidates to talent pool for future opportunities
- View and manage talent pool candidates with filters (status, experience, skills)
- Get comprehensive job analytics: applications by status, AI screening stats, department-wise breakdown
- Support for full recruitment lifecycle: posting → screening → interviewing → hiring

**Platform Insights:**
- Get overall platform statistics (employees, projects, invoices, etc.)
- View activity metrics (GitHub, attendance, UAT)
- Cross-platform search across all data types

**Email Workflow:**
When user wants to send an email:
1. Ask who should receive it (employee name/ID or email address)
2. Ask for the email subject
3. Ask for the purpose and any key points to include
4. Generate a draft email and present it to the user
5. Ask if they want any changes or if it's ready to send
6. If changes needed, regenerate with feedback
7. Once approved, send the email and confirm delivery

When users ask questions, use the appropriate tools to fetch real-time data. Be helpful, concise, and professional. Present data in a clear, organized manner. Today's date is " . now()->format('F j, Y') . ".";
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

    /**
     * Get attendance data
     */
    private function getAttendanceData(array $args): array
    {
        $query = \App\Models\Attendance::with('employee');

        if (isset($args['employee_id'])) {
            $query->where('employee_id', $args['employee_id']);
        }

        if (isset($args['month'])) {
            $date = Carbon::parse($args['month']);
            $query->whereYear('date', $date->year)
                  ->whereMonth('date', $date->month);
        }

        $limit = $args['limit'] ?? 50;
        $attendance = $query->latest('date')->limit($limit)->get();

        return [
            'count' => $attendance->count(),
            'records' => $attendance->map(function($record) {
                return [
                    'date' => $record->date->format('Y-m-d'),
                    'employee' => $record->employee ? $record->employee->first_name . ' ' . $record->employee->last_name : 'Unknown',
                    'status' => $record->status,
                    'check_in' => $record->check_in,
                    'check_out' => $record->check_out,
                    'notes' => $record->notes
                ];
            })->toArray()
        ];
    }

    /**
     * Get projects data
     */
    private function getProjectsData(array $args): array
    {
        $query = \App\Models\Project::query();

        if (isset($args['project_id'])) {
            $project = $query->find($args['project_id']);
            if (!$project) {
                return ['error' => 'Project not found'];
            }

            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status,
                'start_date' => $project->start_date?->format('Y-m-d'),
                'end_date' => $project->end_date?->format('Y-m-d'),
                'client_name' => $project->client_name,
                'created_at' => $project->created_at->format('Y-m-d')
            ];
        }

        if (isset($args['status'])) {
            $query->where('status', $args['status']);
        }

        $projects = $query->get();

        return [
            'count' => $projects->count(),
            'projects' => $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'status' => $project->status,
                    'client' => $project->client_name,
                    'start_date' => $project->start_date?->format('Y-m-d')
                ];
            })->toArray()
        ];
    }

    /**
     * Get UAT data
     */
    private function getUatData(array $args): array
    {
        $query = \App\Models\UatProject::query();

        if (isset($args['project_id'])) {
            $project = $query->with('testCases')->find($args['project_id']);
            if (!$project) {
                return ['error' => 'UAT project not found'];
            }

            $result = [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status,
                'created_at' => $project->created_at->format('Y-m-d')
            ];

            if ($args['include_test_cases'] ?? true) {
                $result['test_cases'] = $project->testCases->map(function($testCase) {
                    return [
                        'id' => $testCase->id,
                        'title' => $testCase->title,
                        'priority' => $testCase->priority,
                        'status' => $testCase->status
                    ];
                })->toArray();
            }

            return $result;
        }

        $projects = $query->withCount('testCases')->get();

        return [
            'count' => $projects->count(),
            'projects' => $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'status' => $project->status,
                    'test_cases_count' => $project->test_cases_count,
                    'created_at' => $project->created_at->format('Y-m-d')
                ];
            })->toArray()
        ];
    }

    /**
     * Get invoices data
     */
    private function getInvoicesData(array $args): array
    {
        $query = \App\Models\Invoice::with('employee');

        if (isset($args['status'])) {
            $query->where('status', $args['status']);
        }

        if (isset($args['employee_id'])) {
            $query->where('employee_id', $args['employee_id']);
        }

        $invoices = $query->latest()->get();

        return [
            'count' => $invoices->count(),
            'total_amount' => $invoices->sum('amount'),
            'invoices' => $invoices->map(function($invoice) {
                return [
                    'invoice_number' => $invoice->invoice_number,
                    'employee' => $invoice->employee ? $invoice->employee->first_name . ' ' . $invoice->employee->last_name : 'N/A',
                    'amount' => $invoice->amount,
                    'currency' => $invoice->currency,
                    'status' => $invoice->status,
                    'issue_date' => $invoice->issue_date?->format('Y-m-d'),
                    'due_date' => $invoice->due_date?->format('Y-m-d')
                ];
            })->toArray()
        ];
    }

    /**
     * Get contracts data
     */
    private function getContractsData(array $args): array
    {
        $query = \App\Models\EmploymentContract::with('employee');

        if (isset($args['employee_id'])) {
            $query->where('employee_id', $args['employee_id']);
        }

        if ($args['active_only'] ?? true) {
            $query->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', Carbon::today());
            });
        }

        $contracts = $query->get();

        return [
            'count' => $contracts->count(),
            'contracts' => $contracts->map(function($contract) {
                return [
                    'id' => $contract->id,
                    'employee' => $contract->employee ? $contract->employee->first_name . ' ' . $contract->employee->last_name : 'Unknown',
                    'position' => $contract->position,
                    'salary' => $contract->salary,
                    'currency' => $contract->currency,
                    'start_date' => $contract->start_date?->format('Y-m-d'),
                    'end_date' => $contract->end_date?->format('Y-m-d'),
                    'is_active' => is_null($contract->end_date) || $contract->end_date >= Carbon::today()
                ];
            })->toArray()
        ];
    }

    /**
     * Get performance reviews
     */
    private function getPerformanceReviews(array $args): array
    {
        $query = \App\Models\PerformanceReview::with(['employee', 'reviewCycle']);

        if (isset($args['employee_id'])) {
            $query->where('employee_id', $args['employee_id']);
        }

        if (isset($args['cycle_id'])) {
            $query->where('review_cycle_id', $args['cycle_id']);
        }

        $reviews = $query->latest()->get();

        return [
            'count' => $reviews->count(),
            'average_rating' => $reviews->avg('overall_rating'),
            'reviews' => $reviews->map(function($review) {
                return [
                    'id' => $review->id,
                    'employee' => $review->employee ? $review->employee->first_name . ' ' . $review->employee->last_name : 'Unknown',
                    'cycle' => $review->reviewCycle ? $review->reviewCycle->name : 'N/A',
                    'overall_rating' => $review->overall_rating,
                    'status' => $review->status,
                    'reviewed_at' => $review->reviewed_at?->format('Y-m-d')
                ];
            })->toArray()
        ];
    }

    /**
     * Get personal notes
     */
    private function getPersonalNotes(array $args): array
    {
        // Get authenticated user from request
        $user = request()->user();

        if (!$user) {
            return ['error' => 'Authentication required to view personal notes'];
        }

        $query = \App\Models\PersonalNote::where('user_id', $user->id);

        if (isset($args['type'])) {
            $query->where('type', $args['type']);
        }

        if (isset($args['search'])) {
            $query->where(function($q) use ($args) {
                $q->where('title', 'like', '%' . $args['search'] . '%')
                  ->orWhere('content', 'like', '%' . $args['search'] . '%');
            });
        }

        $notes = $query->latest()->get();

        return [
            'count' => $notes->count(),
            'notes' => $notes->map(function($note) {
                return [
                    'id' => $note->id,
                    'title' => $note->title,
                    'type' => $note->type,
                    'category' => $note->category,
                    'created_at' => $note->created_at->format('Y-m-d'),
                    'has_reminder' => !is_null($note->reminder_date)
                ];
            })->toArray()
        ];
    }

    /**
     * Create a personal note
     */
    private function createPersonalNote(array $args): array
    {
        try {
            // Get authenticated user from request
            $user = request()->user();

            if (!$user) {
                return ['error' => 'Authentication required to create personal notes'];
            }

            // Validate type
            $validTypes = ['text', 'password', 'backup_code', 'website_link', 'file'];
            $type = $args['type'] ?? 'text';

            if (!in_array($type, $validTypes)) {
                return ['error' => 'Invalid note type. Valid types: text, password, backup_code, website_link, file'];
            }

            // Prepare note data
            $noteData = [
                'user_id' => $user->id,
                'title' => $args['title'],
                'type' => $type,
                'content' => $args['content'] ?? null,
                'url' => $args['url'] ?? null,
            ];

            // Handle reminder time if provided
            if (isset($args['reminder_time'])) {
                try {
                    $noteData['reminder_time'] = Carbon::parse($args['reminder_time']);
                    $noteData['reminder_sent'] = false;
                } catch (\Exception $e) {
                    return ['error' => 'Invalid reminder time format. Use YYYY-MM-DD HH:MM:SS'];
                }
            }

            // Create the note
            $note = \App\Models\PersonalNote::create($noteData);

            return [
                'success' => true,
                'message' => "Personal note '{$note->title}' created successfully",
                'note' => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'type' => $note->type,
                    'content' => $note->content,
                    'url' => $note->url,
                    'has_reminder' => !is_null($note->reminder_time),
                    'reminder_time' => $note->reminder_time ? $note->reminder_time->format('Y-m-d H:i:s') : null,
                    'created_at' => $note->created_at->format('Y-m-d H:i:s')
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error creating personal note: ' . $e->getMessage());
            return ['error' => 'Failed to create personal note: ' . $e->getMessage()];
        }
    }

    /**
     * Get platform statistics
     */
    private function getPlatformStatistics(): array
    {
        $employees = \App\Models\Employee::query();
        $activeEmployees = (clone $employees)->whereNull('discontinued_at')->count();
        $totalEmployees = $employees->count();

        $githubToday = \App\Models\GitHubLog::whereDate('event_at', Carbon::today())->count();
        $githubThisWeek = \App\Models\GitHubLog::whereBetween('event_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $activeProjects = \App\Models\Project::where('status', 'active')->count();
        $totalProjects = \App\Models\Project::count();

        $pendingInvoices = \App\Models\Invoice::where('status', 'unpaid')->count();
        $totalInvoiceAmount = \App\Models\Invoice::where('status', 'unpaid')->sum('amount');

        $uatProjects = \App\Models\UatProject::count();
        $uatTestCases = \App\Models\UatTestCase::count();

        return [
            'employees' => [
                'total' => $totalEmployees,
                'active' => $activeEmployees,
                'discontinued' => $totalEmployees - $activeEmployees
            ],
            'github_activity' => [
                'today' => $githubToday,
                'this_week' => $githubThisWeek,
                'active_developers_today' => \App\Models\GitHubLog::whereDate('event_at', Carbon::today())
                    ->distinct('employee_id')
                    ->count('employee_id')
            ],
            'projects' => [
                'total' => $totalProjects,
                'active' => $activeProjects,
                'completed' => \App\Models\Project::where('status', 'completed')->count()
            ],
            'invoices' => [
                'pending_count' => $pendingInvoices,
                'pending_amount' => $totalInvoiceAmount,
                'paid_count' => \App\Models\Invoice::where('status', 'paid')->count()
            ],
            'uat' => [
                'projects' => $uatProjects,
                'test_cases' => $uatTestCases,
                'passed_tests' => \App\Models\UatTestCase::where('status', 'passed')->count()
            ],
            'attendance' => [
                'records_this_month' => \App\Models\Attendance::whereYear('date', Carbon::now()->year)
                    ->whereMonth('date', Carbon::now()->month)
                    ->count()
            ]
        ];
    }

    /**
     * Search platform data
     */
    private function searchPlatformData(array $args): array
    {
        $query = $args['query'];
        $categories = $args['categories'] ?? ['employees', 'projects', 'uat', 'invoices', 'contracts'];
        $results = [];

        // Search employees
        if (in_array('employees', $categories)) {
            $employees = Employee::where(function($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                  ->orWhere('last_name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('github_username', 'like', '%' . $query . '%')
                  ->orWhere('position', 'like', '%' . $query . '%');
            })->limit(10)->get();

            if ($employees->count() > 0) {
                $results['employees'] = $employees->map(function($emp) {
                    return [
                        'id' => $emp->id,
                        'name' => $emp->first_name . ' ' . $emp->last_name,
                        'position' => $emp->position,
                        'email' => $emp->email
                    ];
                })->toArray();
            }
        }

        // Search projects
        if (in_array('projects', $categories)) {
            $projects = \App\Models\Project::where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('client_name', 'like', '%' . $query . '%');
            })->limit(10)->get();

            if ($projects->count() > 0) {
                $results['projects'] = $projects->map(function($project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->name,
                        'status' => $project->status
                    ];
                })->toArray();
            }
        }

        // Search UAT
        if (in_array('uat', $categories)) {
            $uatProjects = \App\Models\UatProject::where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })->limit(10)->get();

            if ($uatProjects->count() > 0) {
                $results['uat_projects'] = $uatProjects->map(function($project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->name,
                        'status' => $project->status
                    ];
                })->toArray();
            }
        }

        // Search invoices
        if (in_array('invoices', $categories)) {
            $invoices = \App\Models\Invoice::where('invoice_number', 'like', '%' . $query . '%')
                ->limit(10)
                ->get();

            if ($invoices->count() > 0) {
                $results['invoices'] = $invoices->map(function($invoice) {
                    return [
                        'invoice_number' => $invoice->invoice_number,
                        'amount' => $invoice->amount,
                        'status' => $invoice->status
                    ];
                })->toArray();
            }
        }

        return [
            'query' => $query,
            'results_count' => collect($results)->flatten(1)->count(),
            'results' => $results
        ];
    }

    /**
     * Create a checklist (template or daily)
     */
    private function createChecklist(array $args): array
    {
        try {
            $employee = Employee::find($args['employee_id']);
            if (!$employee) {
                return ['error' => 'Employee not found'];
            }

            if ($args['type'] === 'template') {
                // Create checklist template
                $template = \App\Models\ChecklistTemplate::create([
                    'employee_id' => $args['employee_id'],
                    'title' => $args['title'],
                    'description' => $args['description'] ?? '',
                    'is_active' => true
                ]);

                // Create template items
                foreach ($args['items'] as $index => $itemText) {
                    \App\Models\ChecklistTemplateItem::create([
                        'checklist_template_id' => $template->id,
                        'title' => $itemText,
                        'order' => $index + 1
                    ]);
                }

                return [
                    'success' => true,
                    'type' => 'template',
                    'checklist_id' => $template->id,
                    'message' => "Checklist template '{$template->title}' created successfully for {$employee->first_name} {$employee->last_name}",
                    'items_count' => count($args['items'])
                ];
            } else {
                // Create daily checklist
                $dailyChecklist = \App\Models\DailyChecklist::create([
                    'employee_id' => $args['employee_id'],
                    'date' => Carbon::today(),
                    'email_token' => \Illuminate\Support\Str::uuid()
                ]);

                // Create daily checklist items
                foreach ($args['items'] as $index => $itemText) {
                    \App\Models\DailyChecklistItem::create([
                        'daily_checklist_id' => $dailyChecklist->id,
                        'title' => $itemText,
                        'order' => $index + 1,
                        'is_completed' => false
                    ]);
                }

                return [
                    'success' => true,
                    'type' => 'daily',
                    'checklist_id' => $dailyChecklist->id,
                    'message' => "Daily checklist created successfully for {$employee->first_name} {$employee->last_name}",
                    'date' => $dailyChecklist->date->format('Y-m-d'),
                    'items_count' => count($args['items'])
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error creating checklist: ' . $e->getMessage());
            return ['error' => 'Failed to create checklist: ' . $e->getMessage()];
        }
    }

    /**
     * Get checklists
     */
    private function getChecklists(array $args): array
    {
        try {
            $type = $args['type'] ?? null;

            if ($type === 'template' || !$type) {
                $query = \App\Models\ChecklistTemplate::with(['employee', 'items']);

                if (isset($args['employee_id'])) {
                    $query->where('employee_id', $args['employee_id']);
                }

                $templates = $query->where('is_active', true)->get();

                $templateData = $templates->map(function($template) {
                    return [
                        'id' => $template->id,
                        'type' => 'template',
                        'title' => $template->title,
                        'description' => $template->description,
                        'employee' => $template->employee ? $template->employee->first_name . ' ' . $template->employee->last_name : 'N/A',
                        'items_count' => $template->items->count(),
                        'items' => $template->items->pluck('title')->toArray()
                    ];
                })->toArray();
            }

            if ($type === 'daily' || !$type) {
                $query = \App\Models\DailyChecklist::with(['employee', 'items']);

                if (isset($args['employee_id'])) {
                    $query->where('employee_id', $args['employee_id']);
                }

                if (isset($args['date'])) {
                    $query->whereDate('date', $args['date']);
                }

                $dailyChecklists = $query->latest('date')->limit(20)->get();

                $dailyData = $dailyChecklists->map(function($checklist) {
                    return [
                        'id' => $checklist->id,
                        'type' => 'daily',
                        'date' => $checklist->date->format('Y-m-d'),
                        'employee' => $checklist->employee ? $checklist->employee->first_name . ' ' . $checklist->employee->last_name : 'N/A',
                        'items_count' => $checklist->items->count(),
                        'completed_count' => $checklist->items->where('is_completed', true)->count(),
                        'completion_percentage' => $checklist->completion_percentage,
                        'items' => $checklist->items->map(function($item) {
                            return [
                                'title' => $item->title,
                                'completed' => $item->is_completed
                            ];
                        })->toArray()
                    ];
                })->toArray();
            }

            $result = [];
            if (isset($templateData)) {
                $result['templates'] = $templateData;
                $result['templates_count'] = count($templateData);
            }
            if (isset($dailyData)) {
                $result['daily_checklists'] = $dailyData;
                $result['daily_count'] = count($dailyData);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting checklists: ' . $e->getMessage());
            return ['error' => 'Failed to get checklists: ' . $e->getMessage()];
        }
    }

    /**
     * Update a checklist
     */
    private function updateChecklist(array $args): array
    {
        try {
            if ($args['checklist_type'] === 'template') {
                $checklist = \App\Models\ChecklistTemplate::find($args['checklist_id']);
                if (!$checklist) {
                    return ['error' => 'Checklist template not found'];
                }

                // Update template
                if (isset($args['title'])) {
                    $checklist->title = $args['title'];
                }
                if (isset($args['description'])) {
                    $checklist->description = $args['description'];
                }
                $checklist->save();

                // Update items if provided
                if (isset($args['items'])) {
                    // Delete old items
                    $checklist->items()->delete();

                    // Create new items
                    foreach ($args['items'] as $index => $itemText) {
                        \App\Models\ChecklistTemplateItem::create([
                            'checklist_template_id' => $checklist->id,
                            'title' => $itemText,
                            'order' => $index + 1
                        ]);
                    }
                }

                return [
                    'success' => true,
                    'message' => "Checklist template '{$checklist->title}' updated successfully",
                    'checklist_id' => $checklist->id
                ];
            } else {
                $checklist = \App\Models\DailyChecklist::find($args['checklist_id']);
                if (!$checklist) {
                    return ['error' => 'Daily checklist not found'];
                }

                // Update items if provided
                if (isset($args['items'])) {
                    // Delete old items
                    $checklist->items()->delete();

                    // Create new items
                    foreach ($args['items'] as $index => $itemText) {
                        \App\Models\DailyChecklistItem::create([
                            'daily_checklist_id' => $checklist->id,
                            'title' => $itemText,
                            'order' => $index + 1,
                            'is_completed' => false
                        ]);
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Daily checklist updated successfully',
                    'checklist_id' => $checklist->id,
                    'date' => $checklist->date->format('Y-m-d')
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error updating checklist: ' . $e->getMessage());
            return ['error' => 'Failed to update checklist: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a checklist
     */
    private function deleteChecklist(array $args): array
    {
        try {
            if ($args['checklist_type'] === 'template') {
                $checklist = \App\Models\ChecklistTemplate::find($args['checklist_id']);
                if (!$checklist) {
                    return ['error' => 'Checklist template not found'];
                }

                $title = $checklist->title;
                $checklist->items()->delete();
                $checklist->delete();

                return [
                    'success' => true,
                    'message' => "Checklist template '{$title}' deleted successfully"
                ];
            } else {
                $checklist = \App\Models\DailyChecklist::find($args['checklist_id']);
                if (!$checklist) {
                    return ['error' => 'Daily checklist not found'];
                }

                $date = $checklist->date->format('Y-m-d');
                $checklist->items()->delete();
                $checklist->delete();

                return [
                    'success' => true,
                    'message' => "Daily checklist for {$date} deleted successfully"
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error deleting checklist: ' . $e->getMessage());
            return ['error' => 'Failed to delete checklist: ' . $e->getMessage()];
        }
    }

    /**
     * Send checklist via email
     */
    private function sendChecklistEmail(array $args): array
    {
        try {
            $checklist = \App\Models\DailyChecklist::with('employee')->find($args['checklist_id']);
            if (!$checklist) {
                return ['error' => 'Daily checklist not found'];
            }

            $employee = Employee::find($args['employee_id']);
            if (!$employee) {
                return ['error' => 'Employee not found'];
            }

            // Send email
            \Illuminate\Support\Facades\Mail::to($employee->email)->send(
                new \App\Mail\DailyChecklistMail($checklist, $employee)
            );

            // Update email sent timestamp
            $checklist->email_sent_at = Carbon::now();
            $checklist->save();

            return [
                'success' => true,
                'message' => "Checklist sent successfully to {$employee->first_name} {$employee->last_name} ({$employee->email})",
                'checklist_id' => $checklist->id,
                'sent_at' => $checklist->email_sent_at->format('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            Log::error('Error sending checklist email: ' . $e->getMessage());
            return ['error' => 'Failed to send checklist email: ' . $e->getMessage()];
        }
    }

    /**
     * Generate email content using AI
     */
    private function generateEmail(array $args): array
    {
        try {
            $tone = $args['tone'] ?? 'professional';
            $keyPoints = $args['key_points'] ?? [];

            $prompt = "Generate a {$tone} email with the following details:\n\n";
            $prompt .= "Recipient: " . ($args['recipient_name'] ?? $args['recipient_email']) . "\n";
            $prompt .= "Subject: {$args['subject']}\n";
            $prompt .= "Purpose: {$args['purpose']}\n";

            if (!empty($keyPoints)) {
                $prompt .= "\nKey points to include:\n";
                foreach ($keyPoints as $point) {
                    $prompt .= "- {$point}\n";
                }
            }

            $prompt .= "\nGenerate a well-formatted email body. Keep it concise and professional.";

            // Call OpenAI to generate email
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->withoutVerifying() // For local development
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert email writer. Generate professional, well-structured emails based on the given requirements.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);

            if ($response->failed()) {
                return ['error' => 'Failed to generate email: ' . $response->body()];
            }

            $generatedBody = $response->json()['choices'][0]['message']['content'];

            return [
                'success' => true,
                'email_draft' => [
                    'recipient_email' => $args['recipient_email'],
                    'recipient_name' => $args['recipient_name'] ?? '',
                    'subject' => $args['subject'],
                    'body' => $generatedBody
                ],
                'message' => 'Email draft generated successfully. Please review and let me know if you want any changes, or confirm to send it.'
            ];
        } catch (\Exception $e) {
            Log::error('Error generating email: ' . $e->getMessage());
            return ['error' => 'Failed to generate email: ' . $e->getMessage()];
        }
    }

    /**
     * Send custom email
     */
    private function sendCustomEmail(array $args): array
    {
        try {
            $recipientEmail = $args['recipient_email'];
            $recipientName = $args['recipient_name'] ?? 'There';
            $subject = $args['subject'];
            $body = $args['body'];

            // Send email using Laravel Mail
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($recipientEmail, $recipientName, $subject, $body) {
                $message->to($recipientEmail, $recipientName)
                    ->subject($subject)
                    ->html($body);
            });

            return [
                'success' => true,
                'message' => "Email sent successfully to {$recipientName} ({$recipientEmail})",
                'subject' => $subject,
                'sent_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            Log::error('Error sending custom email: ' . $e->getMessage());
            return ['error' => 'Failed to send email: ' . $e->getMessage()];
        }
    }

    /**
     * Get comprehensive employee profile
     */
    private function getEmployeeProfile(array $args): array
    {
        try {
            $employee = Employee::find($args['employee_id']);
            if (!$employee) {
                return ['error' => 'Employee not found'];
            }

            $include = $args['include'] ?? ['all'];
            $includeAll = in_array('all', $include);

            $profile = [
                'employee' => [
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
                    'status' => $employee->discontinued_at ? 'Discontinued' : 'Active'
                ]
            ];

            // Include contracts
            if ($includeAll || in_array('contracts', $include)) {
                $contracts = $employee->contracts()->get();
                $profile['contracts'] = [
                    'count' => $contracts->count(),
                    'list' => $contracts->map(function($contract) {
                        return [
                            'id' => $contract->id,
                            'position' => $contract->position,
                            'salary' => $contract->salary,
                            'currency' => $contract->currency,
                            'start_date' => $contract->start_date?->format('Y-m-d'),
                            'end_date' => $contract->end_date?->format('Y-m-d'),
                            'is_active' => is_null($contract->end_date) || $contract->end_date >= Carbon::today()
                        ];
                    })->toArray()
                ];
            }

            // Include checklists
            if ($includeAll || in_array('checklists', $include)) {
                $dailyChecklists = $employee->dailyChecklists()->latest('date')->limit(10)->get();
                $profile['daily_checklists'] = [
                    'count' => $dailyChecklists->count(),
                    'recent' => $dailyChecklists->map(function($checklist) {
                        return [
                            'id' => $checklist->id,
                            'date' => $checklist->date->format('Y-m-d'),
                            'items_count' => $checklist->items->count(),
                            'completion_percentage' => $checklist->completion_percentage
                        ];
                    })->toArray()
                ];
            }

            // Include GitHub activity
            if ($includeAll || in_array('github', $include)) {
                $githubLogs = $employee->githubLogs()->limit(20)->get();
                $profile['github_activity'] = [
                    'total_activities' => $githubLogs->count(),
                    'recent' => $githubLogs->map(function($log) {
                        return [
                            'event_type' => $log->event_type,
                            'repository' => $log->repository_name,
                            'commits_count' => $log->commits_count,
                            'event_at' => $log->event_at->format('Y-m-d H:i:s')
                        ];
                    })->toArray()
                ];
            }

            // Include attendance
            if ($includeAll || in_array('attendance', $include)) {
                $attendance = $employee->attendances()->limit(20)->get();
                $profile['attendance'] = [
                    'total_records' => $attendance->count(),
                    'recent' => $attendance->map(function($record) {
                        return [
                            'date' => $record->date->format('Y-m-d'),
                            'status' => $record->status,
                            'check_in' => $record->check_in,
                            'check_out' => $record->check_out
                        ];
                    })->toArray()
                ];
            }

            // Include payments
            if ($includeAll || in_array('payments', $include)) {
                $payments = $employee->payments()->limit(10)->get();
                $profile['payments'] = [
                    'total_payments' => $payments->count(),
                    'recent' => $payments->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'amount' => $payment->amount,
                            'currency' => $payment->currency,
                            'paid_at' => $payment->paid_at?->format('Y-m-d'),
                            'payment_method' => $payment->payment_method
                        ];
                    })->toArray()
                ];
            }

            // Include access credentials
            if ($includeAll || in_array('access', $include)) {
                $access = $employee->accesses()->get();
                $profile['access_credentials'] = [
                    'count' => $access->count(),
                    'list' => $access->map(function($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'has_notes' => !empty($item->note_markdown),
                            'has_attachment' => !empty($item->attachment_path)
                        ];
                    })->toArray()
                ];
            }

            return $profile;
        } catch (\Exception $e) {
            Log::error('Error getting employee profile: ' . $e->getMessage());
            return ['error' => 'Failed to get employee profile: ' . $e->getMessage()];
        }
    }

    /**
     * Update employee profile
     */
    private function updateEmployeeProfile(array $args): array
    {
        try {
            $employee = Employee::find($args['employee_id']);
            if (!$employee) {
                return ['error' => 'Employee not found'];
            }

            $updated = [];
            $updatableFields = [
                'first_name', 'last_name', 'email', 'github_username',
                'phone', 'position', 'department', 'salary', 'currency'
            ];

            foreach ($updatableFields as $field) {
                if (isset($args[$field])) {
                    $employee->$field = $args[$field];
                    $updated[] = $field;
                }
            }

            if (!empty($updated)) {
                $employee->save();
            }

            return [
                'success' => true,
                'message' => "Employee profile updated successfully",
                'employee_id' => $employee->id,
                'updated_fields' => $updated,
                'employee' => [
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'email' => $employee->email,
                    'position' => $employee->position,
                    'department' => $employee->department
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error updating employee profile: ' . $e->getMessage());
            return ['error' => 'Failed to update employee profile: ' . $e->getMessage()];
        }
    }

    /**
     * Add activity log
     */
    private function addActivityLog(array $args): array
    {
        try {
            $user = request()->user();
            if (!$user) {
                return ['error' => 'Authentication required to add activity logs'];
            }

            $log = \App\Models\ActivityNote::create([
                'employee_payment_id' => $args['employee_payment_id'] ?? null,
                'user_id' => $user->id,
                'note' => $args['note']
            ]);

            return [
                'success' => true,
                'message' => 'Activity log added successfully',
                'log_id' => $log->id,
                'note' => $log->note,
                'created_at' => $log->created_at->format('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            Log::error('Error adding activity log: ' . $e->getMessage());
            return ['error' => 'Failed to add activity log: ' . $e->getMessage()];
        }
    }

    /**
     * Get employee activity logs
     */
    private function getEmployeeActivityLogs(array $args): array
    {
        try {
            $query = \App\Models\ActivityNote::with(['payment.employee', 'user']);

            if (isset($args['employee_payment_id'])) {
                $query->where('employee_payment_id', $args['employee_payment_id']);
            }

            $limit = $args['limit'] ?? 50;
            $logs = $query->latest()->limit($limit)->get();

            return [
                'count' => $logs->count(),
                'logs' => $logs->map(function($log) {
                    return [
                        'id' => $log->id,
                        'note' => $log->note,
                        'employee' => $log->payment && $log->payment->employee
                            ? $log->payment->employee->first_name . ' ' . $log->payment->employee->last_name
                            : 'N/A',
                        'payment_id' => $log->employee_payment_id,
                        'logged_by' => $log->user ? $log->user->name : 'Unknown',
                        'created_at' => $log->created_at->format('Y-m-d H:i:s')
                    ];
                })->toArray()
            ];
        } catch (\Exception $e) {
            Log::error('Error getting activity logs: ' . $e->getMessage());
            return ['error' => 'Failed to get activity logs: ' . $e->getMessage()];
        }
    }

    /**
     * Manage employee access credentials
     */
    private function manageEmployeeAccess(array $args): array
    {
        try {
            $employee = Employee::find($args['employee_id']);
            if (!$employee) {
                return ['error' => 'Employee not found'];
            }

            if ($args['action'] === 'add') {
                if (!isset($args['title'])) {
                    return ['error' => 'Title is required for adding access'];
                }

                $access = \App\Models\EmployeeAccess::create([
                    'employee_id' => $args['employee_id'],
                    'title' => $args['title'],
                    'note_markdown' => $args['note_markdown'] ?? ''
                ]);

                return [
                    'success' => true,
                    'message' => "Access credential '{$args['title']}' added for {$employee->first_name} {$employee->last_name}",
                    'access_id' => $access->id,
                    'title' => $access->title
                ];
            } else {
                // List access
                $accesses = $employee->accesses()->get();

                return [
                    'employee' => $employee->first_name . ' ' . $employee->last_name,
                    'count' => $accesses->count(),
                    'access_list' => $accesses->map(function($access) {
                        return [
                            'id' => $access->id,
                            'title' => $access->title,
                            'has_notes' => !empty($access->note_markdown),
                            'has_attachment' => !empty($access->attachment_path),
                            'created_at' => $access->created_at->format('Y-m-d H:i:s')
                        ];
                    })->toArray()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error managing employee access: ' . $e->getMessage());
            return ['error' => 'Failed to manage employee access: ' . $e->getMessage()];
        }
    }

    /**
     * Get employee GitHub activity
     */
    private function getEmployeeGitHubActivity(array $args): array
    {
        try {
            $employee = Employee::find($args['employee_id']);
            if (!$employee) {
                return ['error' => 'Employee not found'];
            }

            $days = $args['days'] ?? 30;
            $startDate = Carbon::now()->subDays($days);

            $query = $employee->githubLogs()->where('event_at', '>=', $startDate);

            if (isset($args['event_type']) && $args['event_type'] !== 'all') {
                $query->where('event_type', $args['event_type']);
            }

            $activities = $query->get();

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
                'period' => [
                    'days' => $days,
                    'from' => $startDate->format('Y-m-d'),
                    'to' => Carbon::now()->format('Y-m-d')
                ],
                'summary' => [
                    'total_activities' => $activities->count(),
                    'pushes' => $pushCount,
                    'pull_requests' => $prCount,
                    'reviews' => $reviewCount,
                    'total_commits' => $totalCommits,
                    'average_commits_per_push' => $pushCount > 0 ? round($totalCommits / $pushCount, 2) : 0
                ],
                'repositories' => $activities->pluck('repository_name')->unique()->values()->toArray(),
                'recent_activities' => $activities->take(20)->map(function($activity) {
                    return [
                        'event_type' => $activity->event_type,
                        'repository' => $activity->repository_name,
                        'commits_count' => $activity->commits_count,
                        'branch' => $activity->branch_name,
                        'event_at' => $activity->event_at->format('Y-m-d H:i:s')
                    ];
                })->toArray()
            ];
        } catch (\Exception $e) {
            Log::error('Error getting employee GitHub activity: ' . $e->getMessage());
            return ['error' => 'Failed to get GitHub activity: ' . $e->getMessage()];
        }
    }

    // ===================================
    // Job Management Methods
    // ===================================

    private function getJobAnalytics(array $args): array
    {
        try {
            $jobPostId = $args['job_post_id'] ?? null;
            return $this->jobService->getJobPostAnalytics($jobPostId);
        } catch (\Exception $e) {
            Log::error('Error getting job analytics: ' . $e->getMessage());
            return ['error' => 'Failed to get job analytics: ' . $e->getMessage()];
        }
    }

    private function listJobPosts(array $args): array
    {
        try {
            $filters = [
                'status' => $args['status'] ?? null,
                'department' => $args['department'] ?? null
            ];
            return $this->jobService->getAllJobPosts(array_filter($filters));
        } catch (\Exception $e) {
            Log::error('Error listing job posts: ' . $e->getMessage());
            return ['error' => 'Failed to list job posts: ' . $e->getMessage()];
        }
    }

    private function createJobPost(array $args): array
    {
        try {
            return $this->jobService->createJobPost($args);
        } catch (\Exception $e) {
            Log::error('Error creating job post: ' . $e->getMessage());
            return ['error' => 'Failed to create job post: ' . $e->getMessage()];
        }
    }

    private function updateJobPost(array $args): array
    {
        try {
            $jobPostId = $args['job_post_id'];
            unset($args['job_post_id']);
            return $this->jobService->updateJobPost($jobPostId, $args);
        } catch (\Exception $e) {
            Log::error('Error updating job post: ' . $e->getMessage());
            return ['error' => 'Failed to update job post: ' . $e->getMessage()];
        }
    }

    private function deleteJobPost(array $args): array
    {
        try {
            return $this->jobService->deleteJobPost($args['job_post_id']);
        } catch (\Exception $e) {
            Log::error('Error deleting job post: ' . $e->getMessage());
            return ['error' => 'Failed to delete job post: ' . $e->getMessage()];
        }
    }

    private function searchApplications(array $args): array
    {
        try {
            return $this->jobService->searchApplications($args);
        } catch (\Exception $e) {
            Log::error('Error searching applications: ' . $e->getMessage());
            return ['error' => 'Failed to search applications: ' . $e->getMessage()];
        }
    }

    private function getApplicationDetails(array $args): array
    {
        try {
            return $this->jobService->getApplicationDetails($args['application_id']);
        } catch (\Exception $e) {
            Log::error('Error getting application details: ' . $e->getMessage());
            return ['error' => 'Failed to get application details: ' . $e->getMessage()];
        }
    }

    private function updateApplicationStatus(array $args): array
    {
        try {
            return $this->jobService->updateApplicationStatus(
                $args['application_id'],
                $args['status'],
                $args['notes'] ?? null
            );
        } catch (\Exception $e) {
            Log::error('Error updating application status: ' . $e->getMessage());
            return ['error' => 'Failed to update application status: ' . $e->getMessage()];
        }
    }

    private function deleteApplication(array $args): array
    {
        try {
            return $this->jobService->deleteApplication($args['application_id']);
        } catch (\Exception $e) {
            Log::error('Error deleting application: ' . $e->getMessage());
            return ['error' => 'Failed to delete application: ' . $e->getMessage()];
        }
    }

    private function addToTalentPool(array $args): array
    {
        try {
            $applicationId = $args['application_id'];
            unset($args['application_id']);
            return $this->jobService->addToTalentPool($applicationId, $args);
        } catch (\Exception $e) {
            Log::error('Error adding to talent pool: ' . $e->getMessage());
            return ['error' => 'Failed to add to talent pool: ' . $e->getMessage()];
        }
    }

    private function getTalentPool(array $args): array
    {
        try {
            return $this->jobService->getTalentPoolCandidates($args);
        } catch (\Exception $e) {
            Log::error('Error getting talent pool: ' . $e->getMessage());
            return ['error' => 'Failed to get talent pool: ' . $e->getMessage()];
        }
    }
}
