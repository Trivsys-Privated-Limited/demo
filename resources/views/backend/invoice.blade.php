<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Invoice #{{ $orders[0]->order_number }}</title>
  <style>
    body { font-family: 'JetBrains Mono', monospace; background:#fff; color:#111; }
    .header { text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse: collapse; margin-bottom:20px; }
    table th, table td { padding:8px; border-bottom:1px solid #ccc; text-align:left; }
    .total { font-weight:700; text-align:right; }
  </style>
</head>
<body>
  <div class="header">
    <h2>Invoice #{{ $orders[0]->order_number }}</h2>
    <p>Table: {{ $orders[0]->table->table_number }} | Date: {{ $orders[0]->created_at->format('d-M-Y H:i') }}</p>
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
      @php $grandTotal = 0; @endphp
      @foreach($orders as $order)
        @php $grandTotal += $order->total; @endphp
        <tr>
          <td>{{ $order->item->name }}</td>
          <td>{{ $order->quantity }}</td>
          <td>{{ $order->total }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="total">
    Grand Total: Rs. {{ $grandTotal }}
  </div>
</body>
</html>
