<!DOCTYPE html>
<html>
<head>
    <title>Shares Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Shares Report</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Share Capital:</strong> ₦{{ number_format($shares->sum('amount_paid'), 2) }}</p>
        <p><strong>Total Share Units:</strong> {{ number_format($shares->sum('number_of_units')) }}</p>
        <p><strong>Total Shareholders:</strong> {{ $shares->unique('user_id')->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Certificate</th>
                <th>Member</th>
                <th>Share Type</th>
                <th>Units</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shares as $share)
            <tr>
                <td>{{ $share->created_at->format('M d, Y') }}</td>
                <td>{{ $share->certificate_number }}</td>
                <td>{{ $share->user->surname }} {{ $share->user->firstname }}</td>
                <td>{{ $share->shareType->name }}</td>
                <td>{{ number_format($share->number_of_units) }}</td>
                <td>₦{{ number_format($share->amount_paid, 2) }}</td>
                <td>{{ ucfirst($share->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
