<!DOCTYPE html>
<html>
<head>
    <title>{{ $booking->guest_name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 40px;
            background: #f9f9f9;
        }
        .invoice-box {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 28px;
            color: #047857;
            margin-bottom: 5px;
        }
        .details, .summary {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .details td, .summary td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .details th, .summary th {
            padding: 10px;
            background-color: #e6fffa;
            color: #065f46;
            text-align: left;
            border: 1px solid #ddd;
        }
        .summary td:last-child {
            font-weight: bold;
            text-align: right;
        }
        .rest-amount {
            font-size: 1
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            font-size: 14px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            {{-- Optional Logo --}}
            {{-- <img src="{{ public_path('logo.png') }}" class="logo"> --}}
            <div class="title">Payment Receipt</div>
            <div>Booking Confirmation</div>
        </div>

        <table class="details">
            <tr>
                <th>Guest Name</th>
                <td>{{ $booking->guest_name }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $booking->location }}</td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <th>Payment ID</th>
                <td>{{ $booking->razorpay_payment_id }}</td>
            </tr>
            <tr>
                <th>Mode of Payment</th>
                <td>
                    @php
                        $modes = [
                            'card' => 'Card (Debit/Credit)',
                            'upi' => 'UPI',
                            'netbanking' => 'Net Banking',
                            'wallet' => 'Wallet',
                            'emi' => 'EMI',
                            'bank_transfer' => 'Bank Transfer'
                        ];
                    @endphp
                    {{ $modes[$booking->payment_method] ?? ucfirst($booking->payment_method) }}
                </td>
            </tr>
        </table>

        <table class="summary">
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>Advance Payment (30%)</td>
                <td>₹{{ number_format($booking->price * 0.3, 2) }}</td>
            </tr>
        </table>
        <p class="rest-amount">Rest Amount (70%) to be paid on arrival.</p>

        <div class="footer">
            Thank you for booking with us!<br>
            This is a computer-generated receipt.
        </div>
    </div>
</body>
</html>
