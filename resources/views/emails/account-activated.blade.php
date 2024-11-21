@component('mail::message')
# Account Activated

Dear {{ $member->title }} {{ $member->firstname }} {{ $member->surname }},

Your OGITECH Cooperative Society account has been approved and activated. You now have full access to all member features.

Member Number: {{ $member->member_no }}

@component('mail::button', ['url' => route('login')])
Login to Your Account
@endcomponent

Best regards,<br>
{{ config('app.name') }}
@endcomponent
