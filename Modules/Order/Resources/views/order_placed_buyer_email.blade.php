@component('mail::message')
Your order placed successfully

Dear: {{ $username }}
<br>
Email: {{ $email }}
<br>
your order are placed at: {{ $created_at }}
<br>
<br>
<br>
Thanks,
<br>
{{ config('app.name') }} team
@endcomponent
