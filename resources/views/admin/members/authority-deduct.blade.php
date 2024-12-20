<!DOCTYPE html>
<html>

<head>
    <title>Authority to Deduct</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.2;
            padding: 10px;
            font-size: 12px;
        }

        .content {
            margin-bottom: 20px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            min-width: 200px;
            display: inline-block;
        }

        .recommendation {
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .header {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="header">
            <h1>OGITECH ACADEMIC STAFF CO-OPERATIVE MULTIPURPOSE SOCIETY</h1>
            <p>ASUP SECRETARIAT, OGUN STATE INSTITUTE OF TECHNOLOGY, IGBESA, OGUN STATE STATE</p>
            <h1>AUTHORITY TO DEDUCT FORM</h1>
            <p>Generated on: {{ now()->format('F d, Y') }}</p>
        </div>
        <p>The Bursar,<br>
            Ogun State Institute of Technology,<br>
            Igbesa, Ogun State.</p>

        <p>Dear Sir,</p>
        <p>DATE: {{ now()->format('F d, Y') }}</p>

        <h3>AUTHORITY TO DEDUCT MEMBERSHIP SUBSCRIPTION FROM MY SALARY</h3>

        <p>This is to inform you of my membership of the OGITECH ACADEMIC STAFF COOPERATIVE MULTIPURPOSE SOCIETY.</p>

        <p>I request therefore that the sum of <u> {{ \App\Helpers\NumberToWords::convert($member->monthly_savings) }} Naira Only</u><br>
            <br>

            (#{{ number_format($member->monthly_savings, 2) }}) be deducted from my salary monthly, and be made payable to the <b> OGITECH ACADEMIC STAFF COOPERATIVE MULTIPURPOSE SOCIETY, A/C No.: 8957020013, BANK: FCMB.</b>
        </p>

        <p>Thank you.</p>
        <p>Yours faithfully,</p>

        <p>
            Signature: <span class="signature-line">
                <img src="{{ public_path('storage/' . $member->signature_image) }}" height="40" alt="Member's Signature">
            </span><br>
            Date: {{ now()->format('F d, Y') }}<br>
            Full Name: {{ $member->firstname }} {{ $member->surname }} {{ $member->othername }}<br>
            Department: {{ $member->department->name }}<br>
            Faculty: {{ $member->faculty->name }}<br>
            Staff ID Number: {{ $member->staff_no }}<br>
            Mobile No.: {{ $member->phone_number }}
        </p>

        <div class="recommendation">
            <h4>RECOMMENDATION:</h4>
            <p>The Bursar, Sir.</p>
            <p>The Applicant has met the requirements as stipulated in the Society's Constitution/Bye-laws.<br>
                This application is hereby recommended.</p>

            <p>
                <img src="{{ public_path('storage/signatures/sec_sign.jpg') }}" height="40" alt="Secretary Signature"><br>
                General Secretary
            </p>

            <p>
                <img src="{{ public_path('storage/signatures/president_sign.jpg') }}" height="40" alt="President Signature"><br>
                President
            </p>

            <p>Date of Consideration: {{ $member->approve_at }}</p>
        </div>
    </div>
</body>

</html>
