<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .order-section { margin: 20px 0; }
        .order-section h3 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background-color: #f0f0f0; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f0f0f0; }
        .footer { background-color: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
            <p>Thank you for your order!</p>
        </div>

        <div class="content">
            <p>Hi {{ $order->first_name }},</p>
            <p>Your order has been received and is being processed. Below are the details of your order.</p>

            <div class="order-section">
                <h3>Order Details</h3>
                <table>
                    <tr>
                        <td><strong>Order Number:</strong></td>
                        <td>{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Order Date:</strong></td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><strong>{{ ucfirst($order->status) }}</strong></td>
                    </tr>
                </table>
            </div>

            <div class="order-section">
                <h3>Items Ordered</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
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
            </div>

            <div class="order-section">
                <h3>Order Summary</h3>
                <table>
                    <tr>
                        <td>Subtotal:</td>
                        <td>${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Tax (10%):</td>
                        <td>${{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td>${{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total:</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="order-section">
                <h3>Shipping Address</h3>
                <p>
                    {{ $order->first_name }} {{ $order->last_name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}<br>
                    {{ $order->country }}<br>
                    {{ $order->phone }}
                </p>
            </div>

            <p>We'll notify you when your order ships. Thank you for shopping with us!</p>
            <p>Best regards,<br><strong>Project X Shop</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; 2026 Project X Shop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
