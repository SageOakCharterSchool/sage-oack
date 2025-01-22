@component('mail::message')
<h2>{{ config('app.name') }}</h2>
<p>
    {{ $mailData['feedback']}}
</p>

@endcomponent
