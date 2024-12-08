@component('mail::message')
# OGUN STATE INSTITUTE OF TECHNOLOGY ACADEMIC STAFF IGBESA (ADO-ODO/OTA) COOPERATIVE MULTIPURPOSE SOCIETY LIMITED

Welcome to OASCMS

Dear {{ $user->title }} {{ $user->firstname }} {{ $user->surname }},

Thank you for joining OGITECH Academic Cooperative Multipurpose Society.
Your membership application form number is {{ $user->member_no }}.

<br />
Please pay ₦2,000 as entrance fee to the Society's bank account details below.
<br />
Account Name: OGITECH Academic Cooperative Multipurpose Society
<br />
Account Number: 8957020013
<br />
Bank: First City Monument Bank (FCMB)
<br />


Your account is currently pending the Management Committee approval. We will notify you once your account has been approved.


General Secretary, OASCMS.

@component('mail::button', ['url' => route('login')])
Kindly login to your account
@endcomponent

Best regards,<br>
General Secretary, OASCMS.

© 2024 OASCMS.. All rights reserved.
@endcomponent
