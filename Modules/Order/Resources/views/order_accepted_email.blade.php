@component('mail::message')
Dear: {{ $username }}

Your order from {{ $outlet_name }} accepted successfully
<br>
<h1>Items</h1>
<hr>
@foreach ($order->items as $item)
{{ $item->product->name }} ({{ $item->quantity }}) <p class="float-right">{{ $item->item_total_price }}</p>
@endforeach
<hr>
<br>

Delivery fees <p class="float-right">{{ $order->delivery_fees }}</p>
<br>
Total price <p class="float-right">{{ $order->delivery_fees + $order->order_total_price }}</p>

<div class="text-center">
    Your order id: {{ $order->id }} <br>
    delivering to: {{ $order->address->address_details }} <br>
    Time of order: {{ $order->created_at }} <br>
    Paid by: {{ $order->payment_method }} <br>
</div>
<br>
<br>
<br>
Thanks,
<br>
{{ config('app.name') }} team
@endcomponent
