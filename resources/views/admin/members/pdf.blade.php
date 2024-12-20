<!DOCTYPE html>
<html>

<head>
    <title>Member Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            margin: 5px 0;
        }

        .header p {
            font-size: 11px;
            margin: 3px 0;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            background: #4f46e5;
            color: white;
            padding: 5px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .label {
            font-weight: bold;
            width: 35%;
            background: #f8fafc;
        }

        .member-photo {
            text-align: center;
            margin-bottom: 15px;
        }

        .member-photo img {
            max-width: 100px;
            border-radius: 50%;
        }

        .signature-section {
            margin-top: 20px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            margin: 0 15px;
        }

        .signature-img {
            max-width: 120px;
            max-height: 50px;
        }

        .member-number {
            font-size: 16px;
            text-align: right;
            color: #4f46e5;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>OGITECH ACADEMIC STAFF CO-OPERATIVE MULTIPURPOSE SOCIETY</h1>
        <p>ASUP SECRETARIAT, OGUN STATE INSTITUTE OF TECHNOLOGY, IGBESA, OGUN STATE STATE</p>
        <h1>MEMBERSHIP APPLICATION FORM</h1>
        <p>Generated on: {{ now()->format('F d, Y') }}</p>

    </div>

    <div class="member-number">
        Member No: {{ $member->member_no }}
    </div>

    <div class="member-photo">
        <img src="{{ public_path('storage/' . $member->member_image) }}" alt="Member Photo">
    </div>

    <div class="section">
        <h2 class="section-title">Personal Information</h2>
        <table>
            <tr>
                <td class="label">Title</td>
                <td>{{ $member->title }}</td>
            </tr>
            <tr>
                <td class="label">Full Name</td>
                <td>{{ $member->firstname }} {{ $member->surname }} {{ $member->othername }}</td>
            </tr>
            <tr>
                <td class="label">Date of Birth</td>
                <td>{{ $member->dob }}</td>
            </tr>
            <tr>
                <td class="label">Nationality</td>
                <td>{{ $member->nationality }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Contact Information</h2>
        <table>
            <tr>
                <td class="label">Email Address</td>
                <td>{{ $member->email }}</td>
            </tr>
            <tr>
                <td class="label">Phone Number</td>
                <td>{{ $member->phone_number }}</td>
            </tr>
            <tr>
                <td class="label">Home Address</td>
                <td>{{ $member->home_address }}</td>
            </tr>
            <tr>
                <td class="label">State</td>
                <td>{{ $member->state->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">LGA</td>
                <td>{{ $member->lga->name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Employment Details</h2>
        <table>
            <tr>
                <td class="label">Staff Number</td>
                <td>{{ $member->staff_no }}</td>
            </tr>
            <tr>
                <td class="label">Faculty</td>
                <td>{{ $member->faculty->name }}</td>
            </tr>
            <tr>
                <td class="label">Department</td>
                <td>{{ $member->department->name }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Next of Kin Information</h2>
        <table>
            <tr>
                <td class="label">Name</td>
                <td>{{ $member->nok }}</td>
            </tr>
            <tr>
                <td class="label">Relationship</td>
                <td>{{ $member->nok_relationship }}</td>
            </tr>
            <tr>
                <td class="label">Phone Number</td>
                <td>{{ $member->nok_phone }}</td>
            </tr>
            <tr>
                <td class="label">Address</td>
                <td>{{ $member->nok_address }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Financial Information</h2>
        <table>
            <tr>
                <td class="label">Monthly Savings</td>
                <td>#{{ number_format($member->monthly_savings, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Share Subscription</td>
                <td>#{{ number_format($member->share_subscription, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Month to Commence</td>
                <td>{{ $member->month_commence }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <img src="{{ public_path('storage/' . $member->signature_image) }}" class="signature-img" alt="Member's Signature">
            <p>Member's Signature</p>
            <p>Date: {{ $member->created_at->format('F d, Y') }}</p>
        </div>
    </div>
    <div class="section">
        <h2 class="section-title">Account Status</h2>
        <table>
            <tr>
                <td class="label">Member Since</td>
                <td>{{ $member->created_at->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Approval Status</td>
                <td>{{ $member->admin_sign === 'Yes' ? 'Approved' : 'Pending' }}</td>
            </tr>
            <tr>
                <td class="label">Account Status</td>
                <td>{{ ucfirst($member->status) }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <img src="{{ public_path('storage/signatures/sec_sign.jpg') }}" class="signature-img" alt="General Secretary Signature">
            <p>General Secretary</p>
            <p>Name:<u>Mr. Eletu, Ahmed Ajiboye</u></p>
            <p>Date:<u>{{ $member->approved_at }}</u></p>
        </div>

        <div class="signature-box">
            <img src="{{ public_path('storage/signatures/president_sign.jpg') }}" class="signature-img" alt="President Signature">
            <p>President</p>
            <p>Name:<u>Mr. Ilo, Hammed Owolabi</u></p>
            <p>Date:<u>{{ $member->approved_at }}</u></p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px; font-size: 12px;">
        <p>This document was automatically generated from OGITECH COOP Management System</p>
    </div>
</body>

</html>
