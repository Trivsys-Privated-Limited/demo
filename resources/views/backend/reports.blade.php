@extends('layout.index')
@section('nav')
    @include('layout.nav')
@endsection
@section('sidebar')
    @include('layout.sidebar')
@endsection

@section('home')
<div class="content-wrapper">
    
    {{-- =======================================================
         1. SUPER ADMIN VIEW
    ======================================================= --}}
    @if(auth()->user()->isSuperAdmin())
 <!--   <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0"><i class="fas fa-crown text-warning"></i>Subscriptions Report</h1>
        </div>
    </div> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-crown text-warning"></i> Subscriptions Report</h1>
                </div>
                <!-- Yahan Button Form Add Kiya Hai -->
                <div class="col-sm-6 text-right">
                    <form action="{{ route('reports.download') }}" method="GET" class="d-inline">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rs {{ number_format($todayRevenue, 2) }}</h3>
                            <p>Today's Earning</p>
                        </div>
                        <div class="icon"><i class="ion ion-cash"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Rs {{ number_format($thisMonthRevenue, 2) }}</h3>
                            <p>This Month's Earning</p>
                        </div>
                        <div class="icon"><i class="ion ion-stats-bars"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $activeSubscriptionsCount }}</h3>
                            <p>Total Active Restaurants</p>
                        </div>
                        <div class="icon"><i class="fas fa-store"></i></div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-dark">
                    <h3 class="card-title">Filter Subscriptions</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-4">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h4 class="text-success mb-3">Filtered Revenue: Rs {{ number_format($rangeRevenue, 2) }}</h4>
                    
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
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
                                    <td class="text-success font-weight-bold">Rs {{ number_format($sub->amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($sub->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Expired</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6">No subscriptions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================
         2. RESTAURANT ADMIN VIEW
    ======================================================= --}}
    @elseif(auth()->user()->isRestaurantAdmin())
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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rs {{ number_format($todayTotal, 2) }}</h3>
                            <p>Today's Revenue</p>
                        </div>
                        <div class="icon"><i class="ion ion-cash"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $todayOrders }}</h3>
                            <p>Today's Orders</p>
                        </div>
                        <div class="icon"><i class="ion ion-bag"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Rs {{ number_format($thisMonthTotal, 2) }}</h3>
                            <p>This Month's Revenue</p>
                        </div>
                        <div class="icon"><i class="ion ion-stats-bars"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $thisMonthOrders }}</h3>
                            <p>This Month's Orders</p>
                        </div>
                        <div class="icon"><i class="ion ion-pie-graph"></i></div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-dark">
                    <h3 class="card-title">Filter Table Reports</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4">
                                <label>Select Table (Optional)</label>
                                <select name="table_id" class="form-control">
                                    <option value="">All Tables</option>
                                    @foreach($allTables as $tbl)
                                        <option value="{{ $tbl->id }}" {{ $selectedTable == $tbl->id ? 'selected' : '' }}>
                                            Table {{ $tbl->table_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="text-info font-weight-bold">Filtered Orders: {{ $totalRangeOrders }}</h5>
                        <h5 class="text-success font-weight-bold">Filtered Revenue: Rs {{ number_format($totalRangeRevenue, 2) }}</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Table No</th>
                                    <th>Today Orders</th>
                                    <th>Today Revenue</th>
                                    <th>Monthly Orders</th>
                                    <th>Monthly Revenue</th>
                                    <th>Filtered Orders (Range)</th>
                                    <th>Filtered Revenue (Range)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tableReports as $report)
                                    <tr>
                                        <td class="font-weight-bold">Table {{ $report->table_number }}</td>
                                        <td>{{ $report->daily_orders }}</td>
                                        <td class="text-success">Rs {{ number_format($report->daily_revenue, 2) }}</td>
                                        <td>{{ $report->monthly_orders }}</td>
                                        <td class="text-primary">Rs {{ number_format($report->monthly_revenue, 2) }}</td>
                                        <td>{{ $report->range_orders }}</td>
                                        <td class="text-info">Rs {{ number_format($report->range_revenue, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-muted">No data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@endsection