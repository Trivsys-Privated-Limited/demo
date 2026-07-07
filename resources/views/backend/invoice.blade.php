<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Receipt #{{ $orders[0]->order_number }}</title>
  @php
      $siteName = config('app.name', 'Restaurant Demo');
      $siteEmail = config('mail.from.address', env('MAIL_FROM_ADDRESS', 'info@example.com'));
      $grandTotal = 0;
      foreach ($orders as $order) {
          $grandTotal += $order->total;
      }
  @endphp
  <style>
    /* Sirf 5px ka margin taake kinaroon se border na kate */
    @page { 
        margin: 5px; 
    } 
    
    body { 
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
        font-size: 11px; 
        color: #000; 
        margin: 0; 
        padding: 0; 
    }
    
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    
    .brand { font-size: 16px; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
    .divider { border-bottom: 1px dashed #000; margin: 8px 0; }
    
    .meta-info { font-size: 10px; line-height: 1.4; margin-bottom: 8px; }
    
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 5px; 
        table-layout: fixed; /* Yeh line text ko screen half karne se rokti hai */
    }
    
    th, td { 
        padding: 4px 0; 
        font-size: 10px; 
        vertical-align: top; 
        word-wrap: break-word; /* Agar naam lamba ho toh automatic agli line par aa jayega */
    }
    th { border-bottom: 1px dashed #000; font-weight: bold; }
    
    /* Columns ki width fix kardi hai taake koi masla na ho */
    .col-item { width: 50%; }
    .col-qty { width: 20%; text-align: center; }
    .col-price { width: 30%; text-align: right; }

    .grand-total { 
        font-size: 13px; 
        font-weight: bold; 
        text-align: right; 
        margin-top: 5px; 
        border-top: 1px dashed #000; 
        padding-top: 5px; 
    }
    
    .footer { text-align: center; font-size: 9px; margin-top: 10px; color: #333; }
  </style>
</head>
<body>

  <div class="text-center">
    <div class="brand">{{ $siteName }}</div>
    <div>{{ $siteEmail }}</div>
  </div>

  <div class="divider"></div>

  <div class="meta-info">
    <strong>Order #:</strong> {{ $orders[0]->order_number }}<br>
    <strong>Date:</strong> {{ $orders[0]->created_at->format('d-M-Y h:i A') }}<br>
    <strong>Table:</strong> {{ $orders[0]->table->table_number }}<br>
    @if($orders[0]->guest)
      <strong>Guest:</strong> {{ $orders[0]->guest->name }}<br>
      <strong>Phone:</strong> {{ $orders[0]->guest->phone }}
    @endif
  </div>

  <div class="divider"></div>

  <table>
    <thead>
      <tr>
        <th class="text-left col-item">Item</th>
        <th class="col-qty">Qty</th>
        <th class="col-price">Rs</th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
        <tr>
          <td class="text-left col-item">{{ $order->item->name }}</td>
          <td class="col-qty">{{ $order->quantity }}</td>
          <td class="col-price">{{ $order->total }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="grand-total">
    Total: Rs. {{ number_format($grandTotal, 2) }}
  </div>

  <div class="divider"></div>

  <div class="footer">
    Thank you for dining with us!<br>
    <strong>{{ $siteName }}</strong>
  </div>

</body>
</html>