<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        .invoice-number {
            color: #666;
            margin-top: 5px;
        }
        .company-info {
            text-align: right;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .bill-to, .invoice-info {
            width: 48%;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            color: #333;
        }
        .detail-line {
            margin-bottom: 5px;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-table thead {
            background-color: #f5f5f5;
        }
        .invoice-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #333;
            font-size: 14px;
        }
        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .totals-table {
            width: 300px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        .totals-row.total {
            border-top: 2px solid #333;
            padding-top: 12px;
            margin-top: 8px;
            font-size: 18px;
            font-weight: bold;
        }
        .notes-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Print Button -->
        <div class="no-print" style="text-align: right; margin-bottom: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Print Invoice
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background-color: #666; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                Close
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div>
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $order->order_number }}</div>
            </div>
            <div class="company-info">
                <div class="company-name">{{ $order->restaurant->name }}</div>
                <div class="company-details">
                    @if($order->restaurant->address)
                        <div>{{ $order->restaurant->address }}</div>
                    @endif
                    @if($order->restaurant->phone)
                        <div>Phone: {{ $order->restaurant->phone }}</div>
                    @endif
                    @if($order->restaurant->email)
                        <div>Email: {{ $order->restaurant->email }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="bill-to">
                <div class="section-title">Bill To:</div>
                <div class="detail-line"><strong>{{ $order->customer_name }}</strong></div>
                @if($order->customer_email)
                    <div class="detail-line">{{ $order->customer_email }}</div>
                @endif
                @if($order->customer_phone)
                    <div class="detail-line">{{ $order->customer_phone }}</div>
                @endif
                @if($order->delivery_address)
                    <div class="detail-line" style="margin-top: 10px;">{{ $order->delivery_address }}</div>
                @endif
            </div>
            <div class="invoice-info">
                <div class="detail-line">
                    <strong>Invoice Date:</strong> {{ $order->created_at->format('M d, Y') }}
                </div>
                <div class="detail-line">
                    <strong>Order Type:</strong> {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                </div>
                <div class="detail-line">
                    <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                </div>
                <div class="detail-line">
                    <strong>Payment Status:</strong>
                    <span class="status-badge status-{{ $order->payment_status }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                @if($order->table_number)
                    <div class="detail-line">
                        <strong>Table:</strong> {{ $order->table_number }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->item_price, 2) }}</td>
                        <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-table">
                <div class="totals-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span>Tax:</span>
                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @if($order->delivery_fee > 0)
                    <div class="totals-row">
                        <span>Delivery Fee:</span>
                        <span>${{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                @endif
                <div class="totals-row total">
                    <span>Total:</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
            <div class="notes-section">
                <div class="notes-title">Special Instructions:</div>
                <div>{{ $order->notes }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p style="margin-top: 10px;">This is a computer-generated invoice and does not require a signature.</p>
        </div>
    </div>

    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
