@component('mail::message')
Welcome {{ $first_name }},

<p>We have recieved your request for resetting your password for {{ config('app.name') }} account</p>

<p>Please click the button below to go to the reset password page:</p>

@component('mail::button', ['url' => $url])
RESET
@endcomponent

<p>You can copy and paste the following url in your browser if the brevious button does not work in your browser:</p>

<p><a href="{{ $url }}">{{ $url }}</a></p>
<br>
<br>
<br>
Thanks,
<br>
{{ config('app.name') }} team
@endcomponent
