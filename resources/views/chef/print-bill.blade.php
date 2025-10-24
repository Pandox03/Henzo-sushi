<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill - Order #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: white;
            color: black;
            line-height: 1.4;
        }
        
        .bill-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 48px;
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
        }
        
        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .restaurant-address {
            font-size: 12px;
            color: #666;
        }
        
        .order-info {
            margin-bottom: 20px;
        }
        
        .order-number {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .order-details {
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .items-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .item-name {
            flex: 1;
        }
        
        .item-qty {
            margin: 0 10px;
        }
        
        .item-price {
            font-weight: bold;
        }
        
        .total-section {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 20px;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .total-final {
            font-size: 16px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #45a049;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .bill-container {
                max-width: none;
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print Bill</button>
    
    <div class="bill-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">H</div>
            <div class="restaurant-name">HENZO SUSHI</div>
            <div class="restaurant-address">Authentic Japanese Cuisine</div>
        </div>
        
        <!-- Order Information -->
        <div class="order-info">
            <div class="order-number">Order #{{ $order->order_number }}</div>
            <div class="order-details">Date: {{ $order->created_at->format('M d, Y H:i') }}</div>
            <div class="order-details">Chef: {{ Auth::user()->name }}</div>
            <div class="order-details">Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</div>
        </div>
        
        <!-- Customer Information -->
        <div class="items-section">
            <div class="section-title">CUSTOMER DETAILS</div>
            <div class="order-details">Name: {{ $order->user->name }}</div>
            <div class="order-details">Phone: {{ $order->phone }}</div>
            <div class="order-details">Address: {{ $order->delivery_address }}</div>
        </div>
        
        <!-- Order Items -->
        <div class="items-section">
            <div class="section-title">ORDER ITEMS</div>
            @foreach($order->orderItems as $item)
            <div class="item">
                <div class="item-name">{{ $item->product->name }}</div>
                <div class="item-qty">x{{ $item->quantity }}</div>
                <div class="item-price">${{ number_format($item->total_price, 2) }}</div>
            </div>
            @endforeach
        </div>
        
        <!-- Special Instructions -->
        @if($order->notes)
        <div class="items-section">
            <div class="section-title">SPECIAL INSTRUCTIONS</div>
            <div class="order-details">{{ $order->notes }}</div>
        </div>
        @endif
        
        <!-- Totals -->
        <div class="total-section">
            <div class="total-line">
                <span>Subtotal:</span>
                <span>${{ number_format($order->total_amount - $order->delivery_fee, 2) }}</span>
            </div>
            @if($order->delivery_fee > 0)
            <div class="total-line">
                <span>Delivery Fee:</span>
                <span>${{ number_format($order->delivery_fee, 2) }}</span>
            </div>
            @endif
            <div class="total-line total-final">
                <span>TOTAL:</span>
                <span>${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>Thank you for choosing Henzo Sushi!</div>
            <div>Order prepared by: {{ Auth::user()->name }}</div>
            <div>Prepared at: {{ now()->format('M d, Y H:i') }}</div>
        </div>
    </div>
    
    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
