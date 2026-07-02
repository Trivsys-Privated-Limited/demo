<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Invoice #{{ $orders[0]->order_number }}</title>
  @php
      $siteName = config('app.name', 'Restaurant Demo');
      $siteEmail = config('mail.from.address', env('MAIL_FROM_ADDRESS', 'info@example.com'));
      $grandTotal = 0;
      foreach ($orders as $order) {
          $grandTotal += $order->total;
      }
  @endphp
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 24px; background:#fff; color:#111; }
    .invoice-box { border: 1px solid #ddd; padding: 24px; border-radius: 10px; }
    .header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px; padding-bottom:15px; border-bottom:2px solid #0D9E8A; }
    .brand { font-size: 24px; font-weight: 700; color: #0D9E8A; }
    .meta { text-align:right; font-size:13px; color:#555; }
    .section { margin-bottom:20px; }
    .info-row { display:flex; justify-content:space-between; gap:20px; margin-bottom:10px; }
    .card { background:#f9f9f9; padding:12px 14px; border-radius:8px; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th { background:#0D9E8A; color:white; padding:10px; text-align:left; }
    td { padding:10px; border-bottom:1px solid #eee; }
    .total-row { text-align:right; font-size:16px; font-weight:700; margin-top:10px; }
    .footer { margin-top:24px; font-size:12px; color:#666; text-align:center; border-top:1px solid #eee; padding-top:10px; }
  </style>
</head>
<body>
  <div class="invoice-box">
    <div class="header">
      <div>
        <div class="brand">{{ $siteName }}</div>
        <div>Email: {{ $siteEmail }}</div>
      </div>
      <div class="meta">
        <div><strong>Invoice #</strong>{{ $orders[0]->order_number }}</div>
        <div>Date: {{ $orders[0]->created_at->format('d-M-Y H:i') }}</div>
      </div>
    </div>

    <div class="section">
      <div class="info-row">
        <div class="card">
          <strong>Restaurant Details</strong><br>
          {{ $siteName }}<br>
          Email: {{ $siteEmail }}
        </div>
        <div class="card">
          <strong>Order Details</strong><br>
          Table: {{ $orders[0]->table->table_number }}<br>
          @if($orders[0]->guest)
            Guest: {{ $orders[0]->guest->name }}<br>
            Phone: {{ $orders[0]->guest->phone }}
          @else
            Guest: N/A
          @endif
        </div>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th>Total (Rs.)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
          <tr>
            <td>{{ $order->item->name }}</td>
            <td>{{ $order->quantity }}</td>
            <td>{{ $order->total }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="total-row">
      Grand Total: Rs. {{ $grandTotal }}
    </div>

    <div class="footer">
      Thank you for dining with us.<br>
      {{ $siteName }} • {{ $siteEmail }}
    </div>
  </div>
</body>
</html>
