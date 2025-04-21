<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>OGITECH COOPERATIVE SOCIETY</h1>
        <h2>Transaction Summary Report</h2>
        <p>{{ $monthName }} {{ $year }} - {{ ucfirst($moneyType) }} Transactions</p>
        @if($departmentName)
            <p>Department: {{ $departmentName }}</p>
        @endif
        @if($facultyName)
            <p>School/Directorate/Centre: {{ $facultyName }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>SN</th>
                <th>Member No</th>
                <th>Full Name</th>
                @foreach($distinctTypes as $type)
                    <th>{{ ucwords(str_replace('_', ' ', $type)) }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $member->member_no }}</td>
                    <td>{{ $member->surname }} {{ $member->firstname }} {{ $member->othername }}</td>
                    @foreach($distinctTypes as $type)
                        <td class="text-right">
                            ₦{{ number_format($member->transaction_data[$type] ?? 0, 2) }}
                        </td>
                    @endforeach
                    <td class="text-right font-bold">
                        ₦{{ number_format($member->total, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 4 + count($distinctTypes) }}" style="text-align: center;">
                        No transactions found for the selected criteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="font-bold">Grand Total</td>
                @foreach($distinctTypes as $type)
                    <td class="text-right font-bold">
                        ₦{{ number_format($members->sum(function($member) use ($type) {
                            return $member->transaction_data[$type] ?? 0;
                        }), 2) }}
                    </td>
                @endforeach
                <td class="text-right font-bold">
                    ₦{{ number_format($members->sum('total'), 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Generated on {{ date('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
