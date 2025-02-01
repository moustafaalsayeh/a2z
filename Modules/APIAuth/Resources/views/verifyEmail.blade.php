@component('mail::message')
Please Verify you account be clicking the button below.

@component('mail::button', ['url' => $url])
Verify Email
@endcomponent
<p>You can copy and paste the following url in your browser if the brevious button does not work in your browser:</p>

<a href="{{ $url }}">{{ $url }}</a>
<br>
<br>
<br>
Thanks,
<br>
{{ config('app.name') }} team
@endcomponent
