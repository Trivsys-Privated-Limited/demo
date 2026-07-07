@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-8">
                    <h1 class="m-0">
                        <i class="fas fa-receipt text-warning mr-2"></i>
                        Subscriptions — <strong>{{ $restaurant->bussiness_name ?? $restaurant->name }}</strong>
                    </h1>
                    <small class="text-muted">{{ $restaurant->email }} | {{ $restaurant->phone }}</small>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="{{ route('subscriptions.create', $restaurant->id) }}" class="btn btn-success">
                        <i class="fas fa-plus mr-1"></i> Add New Subscription
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary ml-1">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            {{-- Current Status Card --}}
            <div class="row mb-3">
                <div class="col-lg-4 col-md-6">
                    <div class="card {{ $restaurant->status === 'active' ? 'card-success' : 'card-danger' }} card-outline">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-store fa-3x mb-3 {{ $restaurant->status === 'active' ? 'text-success' : 'text-danger' }}"></i>
                            <h4>Account Status</h4>
                            @if($restaurant->status === 'active')
                                <span class="badge badge-success px-3 py-2" style="font-size:14px">
                                    <i class="fas fa-check-circle mr-1"></i> ACTIVE
                                </span>
                            @else
                                <span class="badge badge-danger px-3 py-2" style="font-size:14px">
                                    <i class="fas fa-times-circle mr-1"></i> INACTIVE
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($activeSubscription)
                <div class="col-lg-4 col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                            <h4>Current Subscription</h4>
                            <p class="mb-1">
                                <strong>{{ $activeSubscription->start_date->format('d M Y') }}</strong>
                                → <strong>{{ $activeSubscription->end_date->format('d M Y') }}</strong>
                            </p>
                            @php
                                $daysLeft = $activeSubscription->daysRemaining();
                            @endphp
                            @if($daysLeft > 7)
                                <span class="badge badge-success px-2 py-1">
                                    <i class="fas fa-clock mr-1"></i> {{ $daysLeft }} days remaining
                                </span>
                            @elseif($daysLeft > 0)
                                <span class="badge badge-warning px-2 py-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Only {{ $daysLeft }} days left!
                                </span>
                            @else
                                <span class="badge badge-danger px-2 py-1">
                                    <i class="fas fa-times-circle mr-1"></i> Expires Today!
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card card-warning card-outline">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-money-bill-wave fa-3x text-warning mb-3"></i>
                            <h4>Current Plan</h4>
                            <h3 class="text-success">Rs {{ number_format($activeSubscription->amount, 0) }}</h3>
                            <p class="text-muted mb-0">{{ $activeSubscription->months }} Month(s) Plan</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-lg-8">
                    <div class="card card-danger card-outline">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                            <h4>No Active Subscription</h4>
                            <p class="text-muted">This restaurant has no active subscription. Add one to activate their account.</p>
                            <a href="{{ route('subscriptions.create', $restaurant->id) }}" class="btn btn-success">
                                <i class="fas fa-plus mr-1"></i> Add Subscription Now
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Subscriptions History Table --}}
            <div class="card card-dark card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i> Subscription History
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Amount Charged</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Added By</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $index => $sub)
                                    <tr class="{{ $sub->status === 'active' && !$sub->isExpired() ? 'table-success' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $sub->start_date->format('d M Y') }}</td>
                                        <td>{{ $sub->end_date->format('d M Y') }}</td>
                                        <td>{{ $sub->months }} Month(s)</td>
                                        <td>
                                            <strong class="text-success">Rs {{ number_format($sub->amount, 0) }}</strong>
                                        </td>
                                        <td>
                                            @if($sub->status === 'active' && !$sub->isExpired())
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i> Active
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $sub->daysRemaining() }} days left</small>
                                            @elseif($sub->status === 'expired' || $sub->isExpired())
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle mr-1"></i> Expired
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $sub->notes ?? '—' }}</td>
                                        <td>{{ $sub->admin->name ?? 'Admin' }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('subscriptions.destroy', $sub->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            No subscription history found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($subscriptions->count())
                <div class="card-footer text-right">
                    <strong>Total Collected: Rs {{ number_format($subscriptions->sum('amount'), 0) }}</strong>
                </div>
                @endif
            </div>

        </div>
    </section>
</div>
@endsection
