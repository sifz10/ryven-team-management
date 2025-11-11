<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationTest;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Date range filter
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Job filter
        $jobPostId = $request->input('job_post_id');

        // Base query
        $query = ApplicationTest::with(['application.jobPost'])
            ->whereNotNull('sent_at')
            ->whereBetween('sent_at', [$startDate, $endDate]);

        if ($jobPostId) {
            $query->whereHas('application', function($q) use ($jobPostId) {
                $q->where('job_post_id', $jobPostId);
            });
        }

        // Overall Statistics
        $totalTests = $query->count();
        $submittedTests = (clone $query)->whereIn('status', ['submitted', 'reviewed'])->count();
        $reviewedTests = (clone $query)->where('status', 'reviewed')->count();
        $pendingTests = (clone $query)->where('status', 'sent')->count();
        $overdueTests = (clone $query)->where('status', 'sent')
            ->where('deadline', '<', now())
            ->count();

        // Completion Rate
        $completionRate = $totalTests > 0 ? round(($submittedTests / $totalTests) * 100, 2) : 0;

        // Average Time to Submit (in hours)
        $avgTimeToSubmit = ApplicationTest::selectRaw('AVG(TIMESTAMPDIFF(HOUR, sent_at, submitted_at)) as avg_hours')
            ->where('status', 'submitted')
            ->whereBetween('sent_at', [$startDate, $endDate])
            ->value('avg_hours');
        $avgTimeToSubmit = round($avgTimeToSubmit ?? 0, 1);

        // Average Score
        $avgScore = ApplicationTest::where('status', 'reviewed')
            ->whereNotNull('score')
            ->whereBetween('sent_at', [$startDate, $endDate])
            ->avg('score');
        $avgScore = round($avgScore ?? 0, 1);

        // Score Distribution (0-40: Poor, 41-60: Fair, 61-80: Good, 81-100: Excellent)
        $scoreDistribution = [
            'poor' => ApplicationTest::where('status', 'reviewed')
                ->whereBetween('score', [0, 40])
                ->whereBetween('sent_at', [$startDate, $endDate])
                ->count(),
            'fair' => ApplicationTest::where('status', 'reviewed')
                ->whereBetween('score', [41, 60])
                ->whereBetween('sent_at', [$startDate, $endDate])
                ->count(),
            'good' => ApplicationTest::where('status', 'reviewed')
                ->whereBetween('score', [61, 80])
                ->whereBetween('sent_at', [$startDate, $endDate])
                ->count(),
            'excellent' => ApplicationTest::where('status', 'reviewed')
                ->whereBetween('score', [81, 100])
                ->whereBetween('sent_at', [$startDate, $endDate])
                ->count(),
        ];

        // Tests by Status (for chart)
        $statusData = [
            'labels' => ['Pending', 'Submitted', 'Reviewed', 'Overdue'],
            'data' => [$pendingTests, $submittedTests, $reviewedTests, $overdueTests],
        ];

        // Daily submission trend (last 30 days)
        $dailyTrend = ApplicationTest::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->where('status', 'submitted')
            ->whereBetween('submitted_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing candidates
        $topCandidates = ApplicationTest::with('application')
            ->where('status', 'reviewed')
            ->whereNotNull('score')
            ->whereBetween('sent_at', [$startDate, $endDate])
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get();

        // Tests by Job Position
        $testsByJob = ApplicationTest::select('job_posts.title', DB::raw('COUNT(*) as total'))
            ->join('job_applications', 'application_tests.job_application_id', '=', 'job_applications.id')
            ->join('job_posts', 'job_applications.job_post_id', '=', 'job_posts.id')
            ->whereBetween('application_tests.sent_at', [$startDate, $endDate])
            ->groupBy('job_posts.id', 'job_posts.title')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Recent activity
        $recentTests = ApplicationTest::with(['application.jobPost'])
            ->whereBetween('sent_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Job posts for filter
        $jobPosts = JobPost::orderBy('title')->get();

        // Get all tests for export
        $allTests = (clone $query)->orderBy('sent_at', 'desc')->get();

        return view('admin.analytics.tests', compact(
            'totalTests',
            'submittedTests',
            'reviewedTests',
            'pendingTests',
            'overdueTests',
            'completionRate',
            'avgTimeToSubmit',
            'avgScore',
            'scoreDistribution',
            'statusData',
            'dailyTrend',
            'topCandidates',
            'testsByJob',
            'recentTests',
            'jobPosts',
            'startDate',
            'endDate',
            'jobPostId',
            'allTests'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $jobPostId = $request->input('job_post_id');

        $query = ApplicationTest::with(['application.jobPost'])
            ->whereBetween('sent_at', [$startDate, $endDate]);

        if ($jobPostId) {
            $query->whereHas('application', function($q) use ($jobPostId) {
                $q->where('job_post_id', $jobPostId);
            });
        }

        $tests = $query->orderBy('sent_at', 'desc')->get();

        if ($format === 'csv') {
            return $this->exportToCsv($tests);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($tests, $startDate, $endDate);
        }

        return back()->with('error', 'Invalid export format');
    }

    private function exportToCsv($tests)
    {
        $filename = 'test_analytics_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($tests) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Test ID',
                'Candidate Name',
                'Email',
                'Job Position',
                'Test Title',
                'Status',
                'Sent Date',
                'Deadline',
                'Submitted Date',
                'Time to Submit (hours)',
                'Score',
                'Feedback'
            ]);

            // Data rows
            foreach ($tests as $test) {
                $timeToSubmit = null;
                if ($test->submitted_at && $test->sent_at) {
                    $timeToSubmit = round($test->sent_at->diffInHours($test->submitted_at), 1);
                }

                fputcsv($file, [
                    $test->id,
                    $test->application->full_name ?? 'N/A',
                    $test->application->email ?? 'N/A',
                    $test->application->jobPost->title ?? 'N/A',
                    $test->test_title,
                    ucfirst($test->status),
                    $test->sent_at ? $test->sent_at->format('Y-m-d H:i') : 'N/A',
                    $test->deadline ? $test->deadline->format('Y-m-d H:i') : 'N/A',
                    $test->submitted_at ? $test->submitted_at->format('Y-m-d H:i') : 'N/A',
                    $timeToSubmit ?? 'N/A',
                    $test->score ?? 'N/A',
                    $test->feedback ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToPdf($tests, $startDate, $endDate)
    {
        // Recalculate statistics for PDF
        $totalTests = $tests->count();
        $submittedTests = $tests->whereIn('status', ['submitted', 'reviewed'])->count();
        $reviewedTests = $tests->where('status', 'reviewed')->count();
        $overdueTests = $tests->where('status', 'sent')->where('deadline', '<', now())->count();
        $completionRate = $totalTests > 0 ? round(($submittedTests / $totalTests) * 100, 2) : 0;

        $avgTimeToSubmit = $tests->filter(function($test) {
            return $test->submitted_at && $test->sent_at;
        })->avg(function($test) {
            return $test->sent_at->diffInHours($test->submitted_at);
        });
        $avgTimeToSubmit = round($avgTimeToSubmit ?? 0, 1);

        $avgScore = $tests->where('status', 'reviewed')->whereNotNull('score')->avg('score');
        $avgScore = round($avgScore ?? 0, 1);

        $scoreDistribution = [
            'poor' => $tests->where('status', 'reviewed')->whereBetween('score', [0, 40])->count(),
            'fair' => $tests->where('status', 'reviewed')->whereBetween('score', [41, 60])->count(),
            'good' => $tests->where('status', 'reviewed')->whereBetween('score', [61, 80])->count(),
            'excellent' => $tests->where('status', 'reviewed')->whereBetween('score', [81, 100])->count(),
        ];

        $topCandidates = $tests->where('status', 'reviewed')->whereNotNull('score')->sortByDesc('score')->take(10);

        $testsByJob = $tests->groupBy('application.jobPost.title')->map(function($group) {
            return (object) ['title' => $group->first()->application->jobPost->title ?? 'N/A', 'total' => $group->count()];
        })->sortByDesc('total')->take(10);

        $jobPostFilter = $tests->first()->application->jobPost->title ?? null;
        $allTests = $tests;

        $pdf = app('dompdf.wrapper');
        $html = view('admin.analytics.pdf', compact(
            'tests', 'startDate', 'endDate', 'totalTests', 'submittedTests', 'reviewedTests',
            'overdueTests', 'completionRate', 'avgTimeToSubmit', 'avgScore', 'scoreDistribution',
            'topCandidates', 'testsByJob', 'jobPostFilter', 'allTests'
        ))->render();

        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('test_analytics_' . now()->format('Y-m-d_His') . '.pdf');
    }
}
