@component('mail::message')
# Hello {{ $first_name }},

Your account has been successfully registered with us. You can login with the below login access credentials.
<br><br>
Email Address: {{ $email }}
<br>
Password: <b>{{ $password }}</b>
<br><br>
You can reset password with below button
@component('mail::button', ['url' => $tokenUrl])
    Create Password
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
