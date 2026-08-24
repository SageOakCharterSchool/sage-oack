@component('mail::message')
<h2>{{ config('app.name') }}</h2>
<p>
    Date:<br>
    {{ date("Y-m-d H:i:s") }}
</p>
<p>
    Total Rows Processed:<br>
    {{ $mailData['totalRows']}}
</p>
<p>
    Total Rows Updated:<br>
    {{ $mailData['totalUpdates']}}
</p>
<p>
    Notes:<br>
    {{ $mailData['notes']}}
</p>

@endcomponent
