@component('mail::message')
{{-- Custom Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('images/bg-login.jpg') }}" alt="Your Logo" width="120">
</div>

# Hello vishal is the developer{{ $user->name ?? 'User' }},

We received a request to reset your password for your **{{ config('app.name') }}** account.

Click the button below to set a new password:

@component('mail::button', ['url' => $url, 'color' => 'blue'])
Reset My Password
@endcomponent

If you didn’t request a password reset, you can safely ignore this email.

Thanks,<br>
**The {{ config('app.name') }} Team**

<hr style="margin-top: 25px;">

<p style="font-size: 12px; color: #6b7280;">
If you’re having trouble clicking the "Reset My Password" button, copy and paste the URL below into your web browser:
<br><a href="{{ $url }}">{{ $url }}</a>
</p>
@endcomponent
