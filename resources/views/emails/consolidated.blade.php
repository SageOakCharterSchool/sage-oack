@component('mail::message')
<h1>Hello {{$name}}</h1>

@component('mail::button', ['url'=>"https://google.com"])
    Register
@endcomponent
@endcomponent
