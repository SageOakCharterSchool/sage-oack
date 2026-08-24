@component('mail::message')
    <p>Dear {{ $user->name }}</p>

    <p>Your requested report is attached to this message</p>


    <p>Thanks</p>
@endcomponent
