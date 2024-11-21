@component('mail::message')
# Welcome to OGITECH COOP

Dear {{ $user->title }} {{ $user->firstname }} {{ $user->surname }},

Thank you for joining OGITECH Cooperative Society. Your membership number is {{ $user->member_no }}.

<br />
Pay ₦2,000 as entrance fee to the society bank account.
<br />
Account Name: OGITECH Cooperative Society
<br />
Account Number: 0000000000
<br />
Bank: First Bank
<br />

Let your Member No. be the narration of the transaction.<br>
Your account is currently pending admin approval. We will notify you once your account has been approved.

@component('mail::button', ['url' => route('login')])
Login to Your Account
@endcomponent

Best regards,<br>
{{ config('app.name') }}
@endcomponent
