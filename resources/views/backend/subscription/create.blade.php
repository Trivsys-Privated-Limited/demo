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
                        <i class="fas fa-plus-circle text-success mr-2"></i>
                        New Subscription — <strong>{{ $restaurant->bussiness_name ?? $restaurant->name }}</strong>
                    </h1>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="{{ route('subscriptions.index', $restaurant->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-invoice-dollar mr-2"></i>
                                Add Subscription / Payment
                            </h3>
                        </div>
                        <form action="{{ route('subscriptions.store', $restaurant->id) }}" method="POST">
                            @csrf
                            <div class="card-body">

                                {{-- Restaurant Info Row --}}
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-store mr-1"></i> Restaurant:</strong>
                                    {{ $restaurant->bussiness_name ?? $restaurant->name }}
                                    &nbsp;|&nbsp;
                                    <strong>Owner:</strong> {{ $restaurant->name }}
                                    &nbsp;|&nbsp;
                                    <strong>Email:</strong> {{ $restaurant->email }}
                                </div>

                                <div class="row">
                                    {{-- Start Date --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date">
                                                <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                                Start Date <span class="text-danger">*</span>
                                            </label>
                                            <input type="date"
                                                   class="form-control @error('start_date') is-invalid @enderror"
                                                   name="start_date"
                                                   id="start_date"
                                                   value="{{ old('start_date', date('Y-m-d')) }}"
                                                   onchange="calculateEndDate()">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Months --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="months">
                                                <i class="fas fa-clock text-warning mr-1"></i>
                                                Duration (Months) <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('months') is-invalid @enderror"
                                                    name="months"
                                                    id="months"
                                                    onchange="calculateEndDate()">
                                                <option value="">-- Select Duration --</option>
                                                @foreach([1,2,3,6,12] as $m)
                                                    <option value="{{ $m }}" {{ old('months') == $m ? 'selected' : '' }}>
                                                        {{ $m }} Month{{ $m > 1 ? 's' : '' }}
                                                    </option>
                                                @endforeach
                                                <option value="custom" {{ old('months') === 'custom' ? 'selected' : '' }}>Custom...</option>
                                            </select>
                                            @error('months')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Custom Months (hidden by default) --}}
                                <div class="row" id="customMonthsRow" style="display:none;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="custom_months">
                                                <i class="fas fa-pencil-alt mr-1"></i> Enter Custom Months
                                            </label>
                                            <input type="number"
                                                   class="form-control"
                                                   id="custom_months"
                                                   placeholder="e.g. 4"
                                                   min="1" max="60"
                                                   onchange="calculateEndDate()">
                                        </div>
                                    </div>
                                </div>

                                {{-- End Date (read-only, auto-calculated) --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                <i class="fas fa-calendar-times text-danger mr-1"></i>
                                                Expiry Date (Auto-calculated)
                                            </label>
                                            <input type="text"
                                                   class="form-control bg-light"
                                                   id="end_date_display"
                                                   placeholder="Select start date & duration"
                                                   readonly>
                                        </div>
                                    </div>

                                    {{-- Amount --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">
                                                <i class="fas fa-money-bill-wave text-success mr-1"></i>
                                                Amount Charged (Rs) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rs</span>
                                                </div>
                                                <input type="number"
                                                       class="form-control @error('amount') is-invalid @enderror"
                                                       name="amount"
                                                       id="amount"
                                                       placeholder="0"
                                                       min="0"
                                                       step="0.01"
                                                       value="{{ old('amount') }}">
                                                @error('amount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div class="form-group">
                                    <label for="notes">
                                        <i class="fas fa-sticky-note text-secondary mr-1"></i>
                                        Notes (Optional)
                                    </label>
                                    <textarea class="form-control"
                                              name="notes"
                                              id="notes"
                                              rows="3"
                                              placeholder="Payment method, reference number, or any note...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Summary Box --}}
                                <div id="summaryBox" class="alert alert-success" style="display:none;">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong id="summaryText"></strong>
                                </div>

                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('subscriptions.index', $restaurant->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check mr-1"></i> Activate Subscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
function calculateEndDate() {
    const startVal   = document.getElementById('start_date').value;
    const monthsSel  = document.getElementById('months').value;
    const customRow  = document.getElementById('customMonthsRow');
    const endDisplay = document.getElementById('end_date_display');
    const summaryBox = document.getElementById('summaryBox');
    const summaryTxt = document.getElementById('summaryText');

    // Show/hide custom months input
    if (monthsSel === 'custom') {
        customRow.style.display = 'flex';
        return;
    } else {
        customRow.style.display = 'none';
        // Put selected value into hidden field logic
        if (monthsSel) {
            document.getElementById('months').dataset.value = monthsSel;
        }
    }

    let months = parseInt(monthsSel);
    if (isNaN(months) || !startVal) {
        endDisplay.value = '';
        summaryBox.style.display = 'none';
        return;
    }

    const start   = new Date(startVal);
    const endDate = new Date(startVal);
    endDate.setMonth(endDate.getMonth() + months);
    endDate.setDate(endDate.getDate() - 1);

    const options = { day: '2-digit', month: 'short', year: 'numeric' };
    endDisplay.value = endDate.toLocaleDateString('en-GB', options);

    summaryTxt.textContent =
        `Subscription will run from ${start.toLocaleDateString('en-GB', options)} to ${endDate.toLocaleDateString('en-GB', options)} (${months} month${months > 1 ? 's' : ''}).`;
    summaryBox.style.display = 'block';
}

// Handle custom months change
document.getElementById('custom_months').addEventListener('change', function() {
    const val = parseInt(this.value);
    if (val > 0) {
        // Swap select to use this value via hidden input trick
        const sel = document.getElementById('months');
        // Add a hidden months field (or just override)
        let hidden = document.getElementById('months_hidden');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'months';
            hidden.id    = 'months_hidden';
            sel.parentNode.appendChild(hidden);
            sel.removeAttribute('name');
        }
        hidden.value = val;

        // Calculate end date
        const startVal   = document.getElementById('start_date').value;
        const endDisplay = document.getElementById('end_date_display');
        const summaryBox = document.getElementById('summaryBox');
        const summaryTxt = document.getElementById('summaryText');

        if (startVal) {
            const endDate = new Date(startVal);
            endDate.setMonth(endDate.getMonth() + val);
            endDate.setDate(endDate.getDate() - 1);
            const options  = { day: '2-digit', month: 'short', year: 'numeric' };
            const startObj = new Date(startVal);
            endDisplay.value = endDate.toLocaleDateString('en-GB', options);
            summaryTxt.textContent = `Subscription will run from ${startObj.toLocaleDateString('en-GB', options)} to ${endDate.toLocaleDateString('en-GB', options)} (${val} month${val > 1 ? 's' : ''}).`;
            summaryBox.style.display = 'block';
        }
    }
});

// Run on page load if values pre-filled
calculateEndDate();
</script>
@endpush
@endsection
