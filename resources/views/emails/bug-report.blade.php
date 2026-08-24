@component('mail::message')
<h2>{{ config('app.name') }}</h2>
<p>
    URL Reported From:<br>
    {{ $mailData['url']}}
</p>
<p>
    Reported By:<br>
    {{ $mailData['created_by_email']}}
</p>
<p>
    Feedback:<br>
    {{ $mailData['feedback']}}
</p>
<p>
    Report Type:<br>
    @if ($mailData['bug_type'] == 1)
        Bug
    @elseif ($mailData['bug_type'] == 2)
        Feature
    @else
        Bug
    @endif
</p>

@endcomponent
