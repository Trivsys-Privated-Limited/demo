<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Statement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0D9E8A;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0D9E8A;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-box td {
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        .summary-box h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .summary-box p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #777;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        table.data-table th {
            background-color: #0D9E8A;
            color: #fff;
            font-size: 13px;
        }
        table.data-table td {
            font-size: 13px;
        }
        .custom-range-title {
            background: #f0f0f0;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Restaurant Sales Statement</h1>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <!-- Summary -->
    <table class="summary-box">
        <tr>
            <td>
                <h3>{{ $todayOrders }}</h3>
                <p>Today's Orders</p>
            </td>
            <td>
                <h3>Rs {{ number_format($todayTotal, 2) }}</h3>
                <p>Today's Sale</p>
            </td>
            <td>
                <h3>{{ $thisMonthOrders }}</h3>
                <p>This Month's Orders</p>
            </td>
            <td>
                <h3>Rs {{ number_format($thisMonthTotal, 2) }}</h3>
                <p>This Month's Sale</p>
            </td>
        </tr>
    </table>

    <!-- Main Table -->
    <h3 style="text-align: center; margin-bottom: 10px;">Table-wise Breakdown (Daily vs Monthly)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2">Table No.</th>
                <th colspan="2">TODAY'S RECORD</th>
                <th colspan="2">MONTHLY RECORD</th>
            </tr>
            <tr>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Orders</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableReports as $report)
                <tr>
                    <td><strong>Table {{ $report->table_number }}</strong></td>
                    <td>{{ $report->daily_orders }}</td>
                    <td style="color: green; font-weight: bold;">Rs {{ number_format($report->daily_revenue, 2) }}</td>
                    <td>{{ $report->monthly_orders }}</td>
                    <td style="color: blue; font-weight: bold;">Rs {{ number_format($report->monthly_revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Custom Range -->
    <div class="custom-range-title">
        Custom Range Record ({{ $startDate }} to {{ $endDate }})
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Table No.</th>
                <th>Total Orders in Range</th>
                <th>Total Sale in Range</th>
            </tr>
        </thead>
        <tbody>
            @php $hasRangeData = false; @endphp
            @foreach($tableReports as $report)
                @if($report->range_orders > 0)
                    @php $hasRangeData = true; @endphp
                    <tr>
                        <td>Table {{ $report->table_number }}</td>
                        <td>{{ $report->range_orders }}</td>
                        <td>Rs {{ number_format($report->range_revenue, 2) }}</td>
                    </tr>
                @endif
            @endforeach
            
            @if(!$hasRangeData)
                <tr>
                    <td colspan="3">No orders found in this custom date range.</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
