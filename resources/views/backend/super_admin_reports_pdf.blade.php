<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscriptions Report</title>
    @php
        $siteName = config('app.name', 'ScanDine');
        // Logged-in Super Admin ki email get karne ke liye:
        $siteEmail = auth()->user()->email ?? 'admin@example.com';
    @endphp
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        .summary-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-box td { width: 33%; padding: 12px; text-align: center; border: 1px solid #ddd; background: #f9f9f9; }
        .summary-box h3 { margin: 0; font-size: 16px; color: #28a745; }
        .summary-box p { margin: 5px 0 0; font-size: 11px; color: #666; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.data-table th, table.data-table td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        table.data-table th { background-color: #343a40; color: #fff; font-size: 12px; }
        .badge-success { color: green; font-weight: bold; }
        .badge-danger { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $siteName }}</h1>
        <p>Email: {{ $siteEmail }}</p>
        <p><strong>Super Admin - Subscriptions Report</strong></p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <!-- Summary -->
    <table class="summary-box">
        <tr>
            <td>
                <h3>Rs {{ number_format($todayRevenue, 2) }}</h3>
                <p>Today's Earning</p>
            </td>
            <td>
                <h3>Rs {{ number_format($thisMonthRevenue, 2) }}</h3>
                <p>This Month's Earning</p>
            </td>
            <td>
                <h3>{{ $activeSubscriptionsCount }}</h3>
                <p>Active Restaurants</p>
            </td>
        </tr>
    </table>

    <h3 style="text-align: center; margin-top: 20px;">
        Subscription Details ({{ $startDate }} to {{ $endDate }})
    </h3>
    <p style="text-align: right; font-weight: bold; color: #28a745;">
        Filtered Total: Rs {{ number_format($rangeRevenue, 2) }}
    </p>

    <table class="data-table">
        <thead>
            <tr>
                <th>Restaurant Name</th>
                <th>Duration</th>
                <th>Amount</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscriptions as $sub)
                <tr>
                    <td>{{ $sub->user->bussiness_name ?? 'N/A' }}</td>
                    <td>{{ $sub->months }} Month(s)</td>
                    <td style="color: green; font-weight: bold;">Rs {{ number_format($sub->amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</td>
                    <td>
                        @if($sub->status == 'active')
                            <span class="badge-success">Active</span>
                        @else
                            <span class="badge-danger">Expired</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No subscription records found in this range.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>