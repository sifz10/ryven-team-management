<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Analytics Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            opacity: 0.9;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .info-box .row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-box .row:last-child {
            margin-bottom: 0;
        }

        .info-box .label {
            font-weight: bold;
            width: 150px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            border-top: 3px solid;
        }

        .stat-card.blue { border-color: #3b82f6; }
        .stat-card.green { border-color: #22c55e; }
        .stat-card.yellow { border-color: #eab308; }
        .stat-card.purple { border-color: #a855f7; }
        .stat-card.red { border-color: #ef4444; }

        .stat-card .label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .stat-card .sub {
            font-size: 9px;
            color: #999;
            margin-top: 3px;
        }

        h2 {
            font-size: 16px;
            margin: 20px 0 10px 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        table th {
            background: #667eea;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        table tr:nth-child(even) {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 600;
        }

        .badge.sent { background: #fef3c7; color: #92400e; }
        .badge.submitted { background: #d1fae5; color: #065f46; }
        .badge.reviewed { background: #dbeafe; color: #1e40af; }

        .score {
            font-weight: bold;
            font-size: 12px;
        }

        .score.excellent { color: #22c55e; }
        .score.good { color: #3b82f6; }
        .score.fair { color: #eab308; }
        .score.poor { color: #ef4444; }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Test Analytics Report</h1>
        <p>Comprehensive Test Performance Analysis</p>
    </div>

    <!-- Report Info -->
    <div class="info-box">
        <div class="row">
            <div class="label">Report Generated:</div>
            <div>{{ now()->format('F d, Y - h:i A') }}</div>
        </div>
        @if($startDate || $endDate)
            <div class="row">
                <div class="label">Date Range:</div>
                <div>
                    @if($startDate)
                        {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                    @else
                        Beginning
                    @endif
                    -
                    @if($endDate)
                        {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                    @else
                        Present
                    @endif
                </div>
            </div>
        @endif
        @if($jobPostFilter)
            <div class="row">
                <div class="label">Job Position:</div>
                <div>{{ $jobPostFilter }}</div>
            </div>
        @endif
    </div>

    <!-- Key Metrics -->
    <h2>Key Metrics Summary</h2>
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="label">Total Tests</div>
            <div class="value">{{ $totalTests }}</div>
        </div>
        <div class="stat-card green">
            <div class="label">Completion</div>
            <div class="value">{{ $completionRate }}%</div>
            <div class="sub">{{ $submittedTests }}/{{ $totalTests }}</div>
        </div>
        <div class="stat-card yellow">
            <div class="label">Avg Time</div>
            <div class="value">{{ $avgTimeToSubmit }}</div>
            <div class="sub">hours</div>
        </div>
        <div class="stat-card purple">
            <div class="label">Avg Score</div>
            <div class="value">{{ $avgScore }}</div>
            <div class="sub">out of 100</div>
        </div>
        <div class="stat-card red">
            <div class="label">Overdue</div>
            <div class="value">{{ $overdueTests }}</div>
        </div>
    </div>

    <!-- Score Distribution -->
    <h2>Score Distribution</h2>
    <table>
        <thead>
            <tr>
                <th>Score Range</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Excellent (81-100)</td>
                <td>{{ $scoreDistribution['excellent'] }}</td>
                <td>{{ $reviewedTests > 0 ? round(($scoreDistribution['excellent'] / $reviewedTests) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Good (61-80)</td>
                <td>{{ $scoreDistribution['good'] }}</td>
                <td>{{ $reviewedTests > 0 ? round(($scoreDistribution['good'] / $reviewedTests) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Fair (41-60)</td>
                <td>{{ $scoreDistribution['fair'] }}</td>
                <td>{{ $reviewedTests > 0 ? round(($scoreDistribution['fair'] / $reviewedTests) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Poor (0-40)</td>
                <td>{{ $scoreDistribution['poor'] }}</td>
                <td>{{ $reviewedTests > 0 ? round(($scoreDistribution['poor'] / $reviewedTests) * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>

    <!-- Top Performers -->
    @if($topCandidates->count() > 0)
        <h2>Top Performers</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Candidate Name</th>
                    <th>Email</th>
                    <th>Test Title</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCandidates as $index => $test)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $test->application->full_name }}</td>
                        <td>{{ $test->application->email }}</td>
                        <td>{{ $test->test_title }}</td>
                        <td>
                            <span class="score {{ $test->score >= 81 ? 'excellent' : ($test->score >= 61 ? 'good' : ($test->score >= 41 ? 'fair' : 'poor')) }}">
                                {{ $test->score }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="page-break"></div>

    <!-- Detailed Test Results -->
    <h2>Detailed Test Results</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Candidate</th>
                <th>Email</th>
                <th>Job Position</th>
                <th>Test Title</th>
                <th>Status</th>
                <th>Sent Date</th>
                <th>Submitted Date</th>
                <th>Time (hrs)</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allTests as $test)
                <tr>
                    <td>{{ $test->id }}</td>
                    <td>{{ $test->application->full_name }}</td>
                    <td>{{ $test->application->email }}</td>
                    <td>{{ $test->application->jobPost->title ?? 'N/A' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($test->test_title, 30) }}</td>
                    <td>
                        @if($test->status === 'sent')
                            <span class="badge sent">Pending</span>
                        @elseif($test->status === 'submitted')
                            <span class="badge submitted">Submitted</span>
                        @elseif($test->status === 'reviewed')
                            <span class="badge reviewed">Reviewed</span>
                        @endif
                    </td>
                    <td>{{ $test->sent_at ? $test->sent_at->format('M d, Y') : '-' }}</td>
                    <td>{{ $test->submitted_at ? $test->submitted_at->format('M d, Y') : '-' }}</td>
                    <td>
                        @if($test->sent_at && $test->submitted_at)
                            {{ round($test->sent_at->diffInHours($test->submitted_at), 1) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($test->score !== null)
                            <span class="score {{ $test->score >= 81 ? 'excellent' : ($test->score >= 61 ? 'good' : ($test->score >= 41 ? 'fair' : 'poor')) }}">
                                {{ $test->score }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tests by Job Position -->
    @if($testsByJob->count() > 0)
        <h2>Tests by Job Position</h2>
        <table>
            <thead>
                <tr>
                    <th>Job Position</th>
                    <th>Total Tests</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testsByJob as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->total }}</td>
                        <td>{{ round(($job->total / $totalTests) * 100, 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by the Test Analytics System on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>For questions or concerns, please contact the HR department.</p>
    </div>
</body>
</html>
