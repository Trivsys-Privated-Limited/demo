@extends('layout.index')
@section('nav')
    @include('layout.nav')
@endsection

@section('sidebar')
    @include('layout.sidebar')
@endsection

@section('home')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Report (Daily vs Monthly)</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <form action="{{ route('reports.download') }}" method="GET" class="d-inline">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <input type="hidden" name="table_id" value="{{ $selectedTable ?? '' }}">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Stats Boxes -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $todayOrders }}</h3>
                            <p>Today's Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rs {{ number_format($todayTotal, 2) }}</h3>
                            <p>Today's Total Sale</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-cash"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $thisMonthOrders }}</h3>
                            <p>This Month's Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-cart"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Rs {{ number_format($thisMonthTotal, 2) }}</h3>
                            <p>This Month's Total Sale</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Data -->
            <div class="card">
                <div class="card-header bg-dark">
                    <h3 class="card-title">Table-wise Sales Breakdown (Daily vs Monthly)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2" class="align-middle border-bottom-0">Table No.</th>
                                <th colspan="2" class="border-bottom-0">TODAY'S RECORD</th>
                                <th colspan="2" class="border-bottom-0">MONTHLY RECORD (THIS MONTH)</th>
                            </tr>
                            <tr>
                                <th>Orders</th>
                                <th>Revenue (Sale)</th>
                                <th>Orders</th>
                                <th>Revenue (Sale)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tableReports as $report)
                                <tr>
                                    <td class="font-weight-bold">Table {{ $report->table_number }}</td>
                                    <td>{{ $report->daily_orders }}</td>
                                    <td class="text-success font-weight-bold">Rs {{ number_format($report->daily_revenue, 2) }}</td>
                                    <td>{{ $report->monthly_orders }}</td>
                                    <td class="text-primary font-weight-bold">Rs {{ number_format($report->monthly_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No tables found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Custom Date Filter Form -->
            <div class="card mt-4">
                <div class="card-header bg-secondary">
                    <h3 class="card-title">Custom Date Filter (Check Old Records)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3"> 
                                <div class="form-group">
                                    <label>Table</label>
                                    <select name="table_id" class="form-control">
                                        <option value="">All Tables</option>
                                        @foreach($allTables as $t)
                                            <option value="{{ $t->id }}" {{ (isset($selectedTable) && $selectedTable == $t->id) ? 'selected' : '' }}>Table {{ $t->table_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 mb-3">Filter</button>
                            </div>
                        </div>
                    </form>
                    
                    <hr>
                    <h5 class="text-center mt-3 mb-3">Custom Range Record ({{ $startDate }} to {{ $endDate }})</h5>
                    <table class="table table-bordered table-sm text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>Table No.</th>
                                <th>Total Orders in Range</th>
                                <th>Total Sale in Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tableReports as $report)
                                @if($report->range_orders > 0)
                                    <tr>
                                        <td>Table {{ $report->table_number }}</td>
                                        <td>{{ $report->range_orders }}</td>
                                        <td>Rs {{ number_format($report->range_revenue, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row mt-3">
                        <div class="col-md-6 offset-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="font-weight-bold">Total Orders in Range</span>
                                        <span>{{ $totalRangeOrders }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="font-weight-bold">Total Sale in Range</span>
                                        <span>Rs {{ number_format($totalRangeRevenue, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
