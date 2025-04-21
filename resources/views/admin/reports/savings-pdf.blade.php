<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Savings Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>OGITECH COOPERATIVE SOCIETY</h1>
        <h2>Savings Report</h2>
        <p>Generated on: {{ date('F d, Y') }}</p>
    </div>

    <div>
        <p><strong>Total Savings:</strong> ₦{{ number_format($totalSavings, 2) }}</p>
        <p><strong>Active Savers:</strong> {{ $activeSavers }}</p>
        <p><strong>Monthly Average:</strong> ₦{{ number_format($monthlyAverage, 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Member</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($savings as $saving)
            <tr>
                <td>{{ $saving->created_at->format('Y-m-d') }}</td>
                <td>{{ optional($saving->user)->surname }} {{ optional($saving->user)->firstname }}</td>
                <td>₦{{ number_format($saving->credit_amount, 2) }}</td>
                <td>{{ ucfirst($saving->status ?? 'completed') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
