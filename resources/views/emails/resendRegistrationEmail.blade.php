@component('mail::message')
    <p>Dear {{ $user->name }}</p>
    <p>Your account has been reset, please login into your account by clicking this link</p>
    <p>
        <strong>Your Login Credentials</strong>
    </p>
    <p>
        Username: {{ $user->email }}<br>
        Password: {{ $password }}
    </p>
    <p>
        <a href="{{ route('login') }}">{{ route('login') }}</a>
    </p>
    <p>Thanks</p>
@endcomponent
