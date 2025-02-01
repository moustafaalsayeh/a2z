@component('mail::message')
<br>
<br>
<h1 class="text-center">You verification code is: {{$token}}</h1>
<br>
<br>
<br>
Thanks,
<br>
{{ config('app.name') }} team
@endcomponent
