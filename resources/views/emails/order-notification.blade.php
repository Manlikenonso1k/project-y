<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .alert { background-color: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background-color: #f0f0f0; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f0f0f0; }
        .footer { background-color: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ New Order Alert</h1>
        </div>

        <div class="content">
            <p>A new order has been placed in your Project X Shop!</p>

            <div class="alert">
                <strong>Order #{{ $order->order_number }}</strong> - {{ $order->created_at->format('M d, Y H:i') }}
            </div>

            <h3>Customer Information</h3>
            <table>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $order->email }}</td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong></td>
                    <td>{{ $order->phone }}</td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td>{{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}, {{ $order->country }}</td>
                </tr>
            </table>

            <h3>Items Ordered</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h3>Order Total</h3>
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax:</td>
                    <td>${{ number_format($order->tax, 2) }}</td>
                </tr>
                <tr>
                    <td>Shipping:</td>
                    <td>${{ number_format($order->shipping, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td>${{ number_format($order->total, 2) }}</td>
                </tr>
            </table>

            <p style="margin-top: 20px;"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p>Log in to your admin dashboard to manage this order.</p>
        </div>

        <div class="footer">
            <p>&copy; 2026 Project X Shop Admin</p>
        </div>
    </div>
</body>
</html>
