<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>MaxFit Performance Receipt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #2b2b2b;
            line-height: 1.4;
            padding: 30px;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .logo-title {
            font-size: 22px;
            font-weight: 800;
            color: #0d6efd;
            letter-spacing: 0.5px;
        }
        .logo-sub {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .receipt-badge {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: bold;
            font-size: 13px;
            padding: 5px 12px;
            border-radius: 4px;
            display: inline-block;
            text-align: right;
        }
        .meta-text {
            font-size: 10px;
            color: #555;
            margin-top: 4px;
            text-align: right;
        }
        
        /* Summary Box */
        .info-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .label-text {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
        }
        .value-text {
            font-size: 11px;
            font-weight: bold;
            color: #212529;
        }

        /* KPI Cards */
        .kpi-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: separate;
            border-spacing: 6px 0;
        }
        .kpi-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-left: 4px solid #0d6efd;
            padding: 8px 10px;
            border-radius: 4px;
            text-align: center;
        }
        .kpi-title {
            font-size: 9px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: bold;
        }
        .kpi-value {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            margin-top: 2px;
        }

        /* Section Headings */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #212529;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 4px;
            margin-top: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        .data-table td {
            font-size: 10px;
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            color: #334155;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge-pill {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 10px;
            background: #e2e8f0;
            color: #475569;
        }

        /* Footer */
        .footer-section {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            width: 100%;
        }
        .signature-box {
            width: 200px;
            border-top: 1px solid #94a3b8;
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: middle;">
                <div class="logo-title">MAXFIT</div>
                <div class="logo-sub">Athlete Workout & Performance Receipt</div>
            </td>
            <td style="vertical-align: middle; text-align: right;">
                <div class="receipt-badge">OFFICIAL RECEIPT</div>
                <div class="meta-text">Generated: {{ now()->format('M d, Y h:i A') }}</div>
                <div class="meta-text">Coach: <strong>{{ $coach->name ?? 'Coach' }}</strong></div>
            </td>
        </tr>
    </table>

    <!-- Group Info Box -->
    <div class="info-box">
        <table class="info-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 35%;">
                    <div class="label-text">Group / Squad Name</div>
                    <div class="value-text">{{ $selectedGroup->name ?? 'All Groups Combined' }}</div>
                </td>
                <td style="width: 35%;">
                    <div class="label-text">Report Period</div>
                    <div class="value-text">
                        @if($startDate && $endDate)
                            {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} &rarr; {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                        @elseif($startDate)
                            From {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                        @elseif($endDate)
                            Until {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                        @else
                            All Time Records
                        @endif
                    </div>
                </td>
                <td style="width: 30%;">
                    <div class="label-text">Exercise Scope</div>
                    <div class="value-text">
                        @if($selectedExercise)
                            {{ $selectedExercise->name }}
                        @else
                            All Assigned Exercises
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- KPI Cards -->
    <table class="kpi-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 25%;">
                <div class="kpi-card">
                    <div class="kpi-title">Active Athletes</div>
                    <div class="kpi-value">{{ $uniqueAthletesCount }}</div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="kpi-card" style="border-left-color: #198754;">
                    <div class="kpi-title">Total Submissions</div>
                    <div class="kpi-value" style="color: #198754;">{{ $totalSubmissions }}</div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="kpi-card" style="border-left-color: #fd7e14;">
                    <div class="kpi-title">Total Output (Reps / Sec)</div>
                    <div class="kpi-value" style="color: #fd7e14;">{{ number_format($totalCountSum) }}</div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="kpi-card" style="border-left-color: #0dcaf0;">
                    <div class="kpi-title">Active Training Days</div>
                    <div class="kpi-value" style="color: #0dcaf0;">{{ $activeDaysCount }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Section 1: Athlete Summary Table -->
    <div class="section-title">1. Athlete Performance Summary</div>
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Athlete Name</th>
                <th>Email</th>
                <th class="text-center" style="width: 80px;">Active Days</th>
                <th class="text-center" style="width: 90px;">Submissions</th>
                <th class="text-end" style="width: 100px;">Total Output</th>
                <th class="text-center" style="width: 90px;">Last Activity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($athleteStats as $index => $stat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $stat['user']->name ?? 'N/A' }}</strong></td>
                    <td style="color: #64748b;">{{ $stat['user']->email ?? 'N/A' }}</td>
                    <td class="text-center">{{ $stat['active_days'] }} days</td>
                    <td class="text-center">{{ $stat['submissions_count'] }}</td>
                    <td class="text-end"><strong>{{ number_format($stat['total_reps']) }}</strong></td>
                    <td class="text-center">{{ $stat['last_submission'] ? \Carbon\Carbon::parse($stat['last_submission'])->format('M d, Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 12px; color: #64748b;">No athlete data found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Section 2: Detailed Activity Log -->
    <div class="section-title">2. Daily Activity & Submissions Log</div>
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 75px;">Date</th>
                <th>Athlete</th>
                <th>Exercise</th>
                <th>Category</th>
                <th class="text-end" style="width: 65px;">Count</th>
                <th style="width: 60px;">Unit</th>
                <th class="text-center" style="width: 75px;">Recorded At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $sub)
                @php
                    $unit = ($sub->exercise->exercise_type === 'sec' || $sub->exercise->exercise_type === 'seconds') ? 'sec' : 'reps';
                @endphp
                <tr>
                    <td><strong>{{ \Carbon\Carbon::parse($sub->submitted_date)->format('M d, Y') }}</strong></td>
                    <td>{{ $sub->user->name ?? 'N/A' }}</td>
                    <td>{{ $sub->exercise->name ?? 'N/A' }}</td>
                    <td style="color: #64748b;">{{ $sub->exercise->exercise_category->name ?? 'N/A' }}</td>
                    <td class="text-end"><strong>{{ $sub->count }}</strong></td>
                    <td><span class="badge-pill">{{ $unit }}</span></td>
                    <td class="text-center" style="color: #64748b; font-size: 9px;">{{ $sub->created_at ? $sub->created_at->format('h:i A') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 12px; color: #64748b;">No submission logs available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer Verification & Signatures -->
    <table class="footer-section" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: top; width: 60%;">
                <div style="font-size: 9.5px; color: #64748b; margin-top: 10px;">
                    <strong>Verification Notice:</strong><br>
                    This is an authentic workout performance receipt certified by MaxFit Athlete Platform.<br>
                    Generated on {{ now()->format('F d, Y \a\t h:i A') }}.
                </div>
            </td>
            <td style="vertical-align: top; text-align: right; width: 40%;">
                <div class="signature-box" style="float: right;">
                    <strong>{{ $coach->name ?? 'Authorized Coach' }}</strong><br>
                    Lead Coach Signature
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
