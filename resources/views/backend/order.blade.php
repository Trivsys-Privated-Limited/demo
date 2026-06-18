@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">All Orders</h1>
            </div>
        </div>

        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @foreach ($orders as $orderNumber => $orderGroup)
                @php
                    $firstOrder = $orderGroup->first();
                @endphp

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
    <div class="row w-100 m-0">
        <div class="col-md-8 p-0">
            <strong>Order #{{ $orderNumber }}</strong> | 
            📍 Table {{ $firstOrder->table->table_number }} <br>
            👤 <strong>Customer:</strong> {{ $firstOrder->guest->name ?? 'Walk-in Guest' }} | 
            📞 <strong>Phone:</strong> {{ $firstOrder->guest->phone ?? 'N/A' }}
        </div>
        <div class="col-md-4 p-0 text-md-right mt-2 mt-md-0">
            <span>Status: <span class="badge badge-light text-dark text-uppercase">{{ $firstOrder->status }}</span></span><br>
            <small>Placed at: {{ $firstOrder->created_at->format('d M Y, h:i A') }}</small>
        </div>
    </div>
</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>Item Name</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderGroup as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $order->item->name }}</td>
                                        <td>{{ Str::limit($order->item->description, 50) }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>Rs. {{ $order->item->price }}</td>
                                        <td>Rs. {{ $order->total }}</td>
                                        <td>{{ $order->note }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Grand Total</th>
                                    <th colspan="2">Rs. {{ $orderGroup->sum('total') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
