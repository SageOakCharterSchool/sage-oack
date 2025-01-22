@component('mail::message')
    <p>Dear {{ $user->name }}</p>
            <p>Your account has been created, please activate your account by clicking this link</p>
            <p>
                <a href="{{ route('verify',$user->email_verification_token) }}">
                {{ route('verify',$user->email_verification_token) }}
                </a>
            </p>
            <p>
                <strong>Your Login Credentials</strong>
            </p>
            <p>
                Username: {{$user->email}}<br>
                Password: {{$password}}
            </p>
        <p>Thanks</p>


@endcomponent
