<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employment Contract - {{ $contract->employee->first_name }} {{ $contract->employee->last_name }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            position: relative;
        }
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: -1;
            width: 500px;
            height: 500px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #1e293b;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
        }
        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18pt;
            font-weight: bold;
            color: #334155;
            margin-top: 10px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #cbd5e1;
        }
        .info-row {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }
        .label {
            font-weight: bold;
            color: #475569;
            width: 40%;
        }
        .value {
            color: #1e293b;
            width: 58%;
        }
        .content {
            text-align: justify;
            margin-bottom: 15px;
            color: #334155;
        }
        .list-item {
            margin-left: 20px;
            margin-bottom: 8px;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            border-top: 2px solid #1e293b;
            padding-top: 10px;
        }
        .signature-label {
            font-weight: bold;
            color: #475569;
            margin-bottom: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #94a3b8;
            padding: 15px 0;
            border-top: 1px solid #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
        }
        .highlight-box {
            background-color: #f8fafc;
            border-left: 4px solid #1e293b;
            padding: 15px;
            margin-bottom: 15px;
        }
        ul {
            margin: 10px 0;
            padding-left: 25px;
        }
        ul li {
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        <img src="{{ public_path('favicon.png') }}" alt="Ryven Circle Logo" style="width: 100%; height: 100%; object-fit: contain;">
    </div>

    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('favicon.png') }}" alt="Ryven Circle Logo" style="width: 100%; height: 100%;">
        </div>
        <div class="company-name">RYVEN CIRCLE</div>
        <div class="document-title">EMPLOYMENT CONTRACT</div>
    </div>

    <!-- Contract Information -->
    <div class="section">
        <div class="section-title">Contract Information</div>
        <table>
            <tr>
                <td class="label">Contract Date:</td>
                <td class="value">{{ $contract->created_at->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Contract Type:</td>
                <td class="value">{{ ucwords(str_replace('_', ' ', $contract->contract_type)) }}</td>
            </tr>
            <tr>
                <td class="label">Contract Status:</td>
                <td class="value">{{ ucwords($contract->status) }}</td>
            </tr>
        </table>
    </div>

    <!-- Parties -->
    <div class="section">
        <div class="section-title">Parties to the Contract</div>
        <div class="content">
            This Employment Contract ("Agreement") is entered into on <strong>{{ $contract->start_date->format('F d, Y') }}</strong>, between:
        </div>
        <div class="highlight-box">
            <strong>EMPLOYER:</strong> Ryven Circle<br>
            <strong>EMPLOYEE:</strong> {{ $contract->employee->first_name }} {{ $contract->employee->last_name }}<br>
            <strong>Email:</strong> {{ $contract->employee->email }}<br>
            <strong>Phone:</strong> {{ $contract->employee->phone }}
        </div>
    </div>

    <!-- Position Details -->
    <div class="section">
        <div class="section-title">Position Details</div>
        <table>
            <tr>
                <td class="label">Job Title:</td>
                <td class="value">{{ $contract->job_title }}</td>
            </tr>
            @if($contract->department)
            <tr>
                <td class="label">Department:</td>
                <td class="value">{{ $contract->department }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Start Date:</td>
                <td class="value">{{ $contract->start_date->format('F d, Y') }}</td>
            </tr>
            @if($contract->end_date)
            <tr>
                <td class="label">End Date:</td>
                <td class="value">{{ $contract->end_date->format('F d, Y') }}</td>
            </tr>
            @endif
        </table>
        @if($contract->job_description)
        <div class="content">
            <strong>Job Description:</strong><br>
            {{ $contract->job_description }}
        </div>
        @endif
    </div>

    <!-- Compensation -->
    <div class="section">
        <div class="section-title">Compensation & Benefits</div>
        <table>
            <tr>
                <td class="label">Salary:</td>
                <td class="value">{{ number_format($contract->salary, 2) }} {{ $contract->currency }}</td>
            </tr>
            <tr>
                <td class="label">Payment Frequency:</td>
                <td class="value">{{ ucwords(str_replace('_', ' ', $contract->payment_frequency)) }}</td>
            </tr>
            <tr>
                <td class="label">Annual Leave:</td>
                <td class="value">{{ $contract->annual_leave_days }} days per year</td>
            </tr>
            <tr>
                <td class="label">Sick Leave:</td>
                <td class="value">{{ $contract->sick_leave_days }} days per year</td>
            </tr>
        </table>
        @if($contract->benefits)
        <div class="content">
            <strong>Additional Benefits:</strong><br>
            {{ $contract->benefits }}
        </div>
        @endif
    </div>

    <!-- Working Conditions -->
    <div class="section">
        <div class="section-title">Working Conditions</div>
        <table>
            <tr>
                <td class="label">Working Hours:</td>
                <td class="value">{{ $contract->working_hours_per_week }} hours per week</td>
            </tr>
            @if($contract->work_schedule)
            <tr>
                <td class="label">Work Schedule:</td>
                <td class="value">{{ $contract->work_schedule }}</td>
            </tr>
            @endif
            @if($contract->work_location)
            <tr>
                <td class="label">Work Location:</td>
                <td class="value">{{ $contract->work_location }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Probation Period:</td>
                <td class="value">{{ $contract->probation_period_days }} days</td>
            </tr>
            <tr>
                <td class="label">Notice Period:</td>
                <td class="value">{{ $contract->notice_period_days }} days</td>
            </tr>
        </table>
    </div>

    @if($contract->responsibilities)
    <!-- Responsibilities -->
    <div class="section">
        <div class="section-title">Key Responsibilities</div>
        <div class="content">
            {{ $contract->responsibilities }}
        </div>
    </div>
    @endif

    <!-- Terms and Conditions -->
    <div class="section">
        <div class="section-title">Terms and Conditions</div>
        <div class="content">
            <strong>1. Probationary Period:</strong> The employee will be subject to a probationary period of {{ $contract->probation_period_days }} days from the start date. During this period, either party may terminate the employment with {{ $contract->notice_period_days }} days' written notice.
        </div>
        <div class="content">
            <strong>2. Termination:</strong> Either party may terminate this employment contract by providing {{ $contract->notice_period_days }} days' written notice to the other party.
        </div>
        <div class="content">
            <strong>3. Confidentiality:</strong> The employee agrees to maintain the confidentiality of all proprietary information and trade secrets of the employer during and after the term of employment.
        </div>
        <div class="content">
            <strong>4. Intellectual Property:</strong> All work products, inventions, and intellectual property created by the employee during the course of employment shall be the sole property of the employer.
        </div>
        @if($contract->additional_terms)
        <div class="content">
            <strong>5. Additional Terms:</strong><br>
            {{ $contract->additional_terms }}
        </div>
        @endif
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-label">Employee Signature:</div>
            <div>{{ $contract->employee->first_name }} {{ $contract->employee->last_name }}</div>
            <div style="margin-top: 5px; font-size: 9pt; color: #64748b;">Date: _________________</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Employer Representative:</div>
            <div>Ryven Circle</div>
            <div style="margin-top: 5px; font-size: 9pt; color: #64748b;">Date: _________________</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <strong>Ryven Circle</strong> | Employment Contract | Generated on {{ now()->format('F d, Y') }}<br>
        This is an official employment contract. Please retain a copy for your records.
    </div>
</body>
</html>

