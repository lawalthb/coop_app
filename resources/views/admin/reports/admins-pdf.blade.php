<!DOCTYPE html>
<html>
<head>
    <title>Administrators Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        .header { text-align: center; margin-bottom: 30px; }
        .total { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Administrators Report</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Admin ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Join Date</th>
                <th>Approved Shares</th>
                <th>Approved Loans</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->member_id }}</td>
                <td>{{ $admin->surname }} {{ $admin->firstname }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->role->name ?? 'N/A' }}</td>
                <td>{{ $admin->created_at->format('M d, Y') }}</td>
                <td>{{ $admin->approved_shares_count }}</td>
                <td>{{ $admin->approved_loans_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
