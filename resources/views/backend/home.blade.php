@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @if(auth()->user()->isSuperAdmin())
                            <h1 class="m-0">
                                <i class="fas fa-crown text-warning mr-2"></i> Super Admin Dashboard
                            </h1>
                        @elseif(auth()->user()->isRestaurantAdmin())
                            <h1 class="m-0">
                                <i class="fas fa-store text-primary mr-2"></i> {{ auth()->user()->bussiness_name ?? auth()->user()->name }} — Dashboard
                            </h1>
                        @else
                            <h1 class="m-0">
                                <i class="fas fa-utensils text-info mr-2"></i> Kitchen — {{ auth()->user()->name }}
                            </h1>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">

                {{-- ================================================================
                     ADMIN/SUPER ADMIN DASHBOARD
                ================================================================ --}}
                @if(auth()->user()->isSuperAdmin())

                    {{-- Admin Stat Boxes --}}
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalUsers }}</h3>
                                    <p>Total Restaurants</p>
                                </div>
                                <div class="icon"><i class="fas fa-store"></i></div>
                                <a href="{{ route('users.index') }}" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalOrders }}</h3>
                                    <p>Total Orders</p>
                                </div>
                                <div class="icon"><i class="ion ion-bag"></i></div>
                                <a href="{{ route('orders.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Rs {{ number_format($todayRevenue, 0) }}</h3>
                                    <p>Today's Revenue</p>
                                </div>
                                <div class="icon"><i class="fas fa-coins"></i></div>
                                <a href="#" class="small-box-footer">All Restaurants <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $pendingOrders }}</h3>
                                    <p>Pending Orders</p>
                                </div>
                                <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                                <a href="{{ route('orders.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    {{-- Revenue Chart (Admin - All Restaurants) --}}
                    <div class="row mt-2">
                        <div class="col-lg-8">
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title">
                                            <i class="fas fa-chart-line mr-2"></i> Overall Revenue Trend
                                        </h3>
                                        <p class="text-sm text-muted mb-0">All restaurants combined</p>
                                    </div>
                                    <form method="GET" action="{{ route('dashboard.index') }}">
                                        <select name="filter" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="today" {{ $filter=='today' ? 'selected' : '' }}>Today</option>
                                            <option value="week"  {{ $filter=='week'  ? 'selected' : '' }}>Last 7 Days</option>
                                            <option value="month" {{ $filter=='month' ? 'selected' : '' }}>Last 30 Days</option>
                                            <option value="year"  {{ $filter=='year'  ? 'selected' : '' }}>This Year</option>
                                            <option value="all"   {{ $filter=='all'   ? 'selected' : '' }}>All Time</option>
                                        </select>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesAnalyticsChart" height="260"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card card-success card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-money-bill-wave mr-2"></i> Revenue Summary</h3>
                                </div>
                                <div class="card-body">
                                    <div class="info-box bg-white mb-3 shadow-sm">
                                        <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Today's Revenue</span>
                                            <span class="info-box-number">Rs {{ number_format($todayRevenue, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="info-box bg-white mb-3 shadow-sm">
                                        <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">This Month Revenue</span>
                                            <span class="info-box-number">Rs {{ number_format($thisMonthRevenue, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="info-box bg-white shadow-sm">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Pending Orders</span>
                                            <span class="info-box-number">{{ $pendingOrders }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Restaurants Table --}}
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <div class="card card-dark card-outline">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">
                                        <i class="fas fa-store mr-2"></i> All Restaurants Overview
                                    </h3>
                                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus mr-1"></i> Add Restaurant
                                    </a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Business Name</th>
                                                    <th>Owner</th>
                                                    <th>Phone</th>
                                                    <th class="text-center">Tables</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-right">Revenue</th>
                                                    <th class="text-center">Subscription Expiry</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($restaurants as $index => $restaurant)
                                                    @php
                                                        $activeSub = $restaurant->subscriptions
                                                            ->where('status', 'active')
                                                            ->where('end_date', '>=', \Carbon\Carbon::today())
                                                            ->sortByDesc('end_date')
                                                            ->first();
                                                        $daysLeft = $activeSub ? \Carbon\Carbon::today()->diffInDays($activeSub->end_date) : 0;
                                                    @endphp
                                                    <tr class="{{ !$activeSub && $restaurant->status === 'active' ? 'table-warning' : ($restaurant->status === 'inactive' ? 'table-danger' : '') }}">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <strong>{{ $restaurant->bussiness_name ?? '—' }}</strong><br>
                                                            <small class="text-muted">{{ $restaurant->email }}</small>
                                                        </td>
                                                        <td>{{ $restaurant->name }}</td>
                                                        <td>{{ $restaurant->phone }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-info">{{ $restaurant->stats['tables'] }}</span><br>
                                                            <small class="text-muted">{{ $restaurant->stats['total_orders'] }} orders</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-primary">{{ $restaurant->stats['total_orders'] }}</span><br>
                                                            <small class="text-muted">Today: {{ $restaurant->stats['today_orders'] }}</small>
                                                        </td>
                                                        <td class="text-right">
                                                            <strong>Rs {{ number_format($restaurant->stats['revenue'], 0) }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($activeSub)
                                                                <div>
                                                                    <small class="text-muted">Expires:</small><br>
                                                                    <strong>{{ $activeSub->end_date->format('d M Y') }}</strong><br>
                                                                    @if($daysLeft > 7)
                                                                        <span class="badge badge-success">{{ $daysLeft }} days left</span>
                                                                    @elseif($daysLeft > 0)
                                                                        <span class="badge badge-warning">⚠ {{ $daysLeft }} days left</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Expires Today!</span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <span class="badge badge-secondary">No Subscription</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($restaurant->status === 'active')
                                                                <span class="badge badge-success px-2 py-1">Active</span>
                                                            @else
                                                                <span class="badge badge-danger px-2 py-1">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center" style="white-space:nowrap;">
                                                            <a href="{{ route('subscriptions.index', $restaurant->id) }}" class="btn btn-xs btn-success mb-1" title="Manage Subscription">
                                                                <i class="fas fa-receipt"></i> Subscription
                                                            </a><br>
                                                            <a href="{{ route('users.edit', $restaurant->id) }}" class="btn btn-xs btn-info" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="{{ route('users.edit_password', $restaurant->id) }}" class="btn btn-xs btn-warning" title="Update Password">
                                                                <i class="fas fa-key"></i>
                                                            </a>
                                                            <a href="{{ route('users.destroy', $restaurant->id) }}" class="btn btn-xs btn-danger" title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this restaurant?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="12" class="text-center text-muted py-4">
                                                            No restaurant users found.
                                                            <a href="{{ route('users.create') }}">Add one now</a>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- ================================================================
                     RESTAURANT ADMIN DASHBOARD
                ================================================================ --}}
                @elseif(auth()->user()->isRestaurantAdmin())

                    {{-- Restaurant Stat Boxes --}}
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalOrders }}</h3>
                                    <p>My Total Orders</p>
                                </div>
                                <div class="icon"><i class="ion ion-bag"></i></div>
                                <a href="{{ route('orders.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalItems }}</h3>
                                    <p>My Menu Items</p>
                                </div>
                                <div class="icon"><i class="ion ion-stats-bars"></i></div>
                                <a href="{{ route('items.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $totalTables }}</h3>
                                    <p>My Tables</p>
                                </div>
                                <div class="icon"><i class="fas fa-chair"></i></div>
                                <a href="{{ route('tables.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $pendingOrders }}</h3>
                                    <p>Pending Orders</p>
                                </div>
                                <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                                <a href="{{ route('orders.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    {{-- Revenue Chart + Summary --}}
                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i> Sales Revenue Trend</h3>
                                        <p class="text-sm text-muted mb-0">Your restaurant's revenue performance</p>
                                    </div>
                                    <form method="GET" action="{{ route('dashboard.index') }}">
                                        <select name="filter" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="today" {{ $filter=='today' ? 'selected' : '' }}>Today</option>
                                            <option value="week"  {{ $filter=='week'  ? 'selected' : '' }}>Last 7 Days</option>
                                            <option value="month" {{ $filter=='month' ? 'selected' : '' }}>Last 30 Days</option>
                                            <option value="year"  {{ $filter=='year'  ? 'selected' : '' }}>This Year</option>
                                            <option value="all"   {{ $filter=='all'   ? 'selected' : '' }}>All Time</option>
                                        </select>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesAnalyticsChart" height="280"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card card-success card-outline mb-3">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-money-bill-wave mr-2"></i> Revenue Summary</h3>
                                </div>
                                <div class="card-body">
                                    <div class="info-box bg-white mb-3 shadow-sm">
                                        <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Today's Revenue</span>
                                            <span class="info-box-number">Rs {{ number_format($todayRevenue, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="info-box bg-white mb-3 shadow-sm">
                                        <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">This Month Revenue</span>
                                            <span class="info-box-number">Rs {{ number_format($thisMonthRevenue, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="info-box bg-white shadow-sm">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Pending Orders</span>
                                            <span class="info-box-number">{{ $pendingOrders }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-warning card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i> Top Items Share</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="topItemsPieChart" height="240"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Top Selling Items --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-light card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-utensils mr-2"></i> Top Selling Items</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @forelse($topItems as $itemData)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $itemData->item->name ?? 'Deleted Item' }}</strong>
                                                    <div class="text-muted text-sm">Sold {{ $itemData->total_qty }} times</div>
                                                </div>
                                                <span class="badge badge-pill badge-primary">{{ $itemData->total_qty }}</span>
                                            </li>
                                        @empty
                                            <li class="list-group-item">No sales recorded yet.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- ================================================================
                     KITCHEN STAFF DASHBOARD
                ================================================================ --}}
                @else

                    {{-- Limited Kitchen View --}}
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalOrders }}</h3>
                                    <p>Today's Orders</p>
                                </div>
                                <div class="icon"><i class="ion ion-bag"></i></div>
                                <a href="{{ route('orders.kitchen') }}" class="small-box-footer">Go to Kitchen <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $pendingOrders }}</h3>
                                    <p>Pending Orders</p>
                                </div>
                                <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                                <a href="{{ route('orders.kitchen') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Rs {{ number_format($todayRevenue, 0) }}</h3>
                                    <p>Today's Revenue</p>
                                </div>
                                <div class="icon"><i class="fas fa-coins"></i></div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>Rs {{ number_format($thisMonthRevenue, 0) }}</h3>
                                    <p>This Month Revenue</p>
                                </div>
                                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>

                @endif

                {{-- Charts Script --}}
                @push('scripts')
                    <script>
                        const analyticsLabels  = @json($analyticsLabels);
                        const analyticsRevenue = @json($analyticsRevenue);

                        if (typeof Chart !== 'undefined') {
                            const lineCtx = document.getElementById('salesAnalyticsChart');
                            if (lineCtx) {
                                new Chart(lineCtx, {
                                    type: 'line',
                                    data: {
                                        labels: analyticsLabels,
                                        datasets: [{
                                            label: 'Revenue',
                                            data: analyticsRevenue,
                                            borderColor: '#007bff',
                                            backgroundColor: 'rgba(0, 123, 255, 0.18)',
                                            pointBackgroundColor: '#004085',
                                            pointBorderColor: '#ffffff',
                                            pointHoverRadius: 6,
                                            fill: true,
                                            tension: 0.35,
                                            borderWidth: 3
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: true, position: 'top', labels: { color: '#333' } },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) { return 'Rs ' + context.formattedValue; }
                                                }
                                            }
                                        },
                                        scales: {
                                            x: { grid: { display: false }, ticks: { color: '#555' } },
                                            y: {
                                                beginAtZero: true,
                                                ticks: { color: '#555', callback: function(value) { return 'Rs ' + value; } }
                                            }
                                        }
                                    }
                                });
                            }

                            @if(auth()->user()->isRestaurantAdmin())
                            const topItemsLabels = @json($topItemsLabels ?? []);
                            const topItemsQty    = @json($topItemsQty ?? []);

                            const pieCtx = document.getElementById('topItemsPieChart');
                            if (pieCtx) {
                                new Chart(pieCtx, {
                                    type: 'pie',
                                    data: {
                                        labels: topItemsLabels,
                                        datasets: [{
                                            data: topItemsQty,
                                            backgroundColor: ['#007bff','#28a745','#ffc107','#17a2b8','#dc3545'],
                                            borderColor: '#ffffff',
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { position: 'bottom', labels: { color: '#333' } },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        return context.label + ': ' + context.formattedValue + ' sold';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                            @endif
                        }
                    </script>
                @endpush

            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
