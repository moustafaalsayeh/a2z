@component('mail::message')
Your have new order

from: {{ $order->user->username }}
<br>
payment method: {{ $order->payment_method }}
<br>
created at: {{ $order->created_at }}
<br>
items:
<br>

<table width="100%" cellpadding="0" cellspacing="0" style="min-width:100%;">
    <thead>
      <tr>
        <th scope="col" style="padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;line-height:30px">
            Product Name
        </th>
        <th scope="col" style="padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;line-height:30px">
            Product Price
        </th>
        <th scope="col" style="padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;line-height:30px">
            Product Quantity
        </th>
      </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td valign="top" style="padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;">
                    {{ $item->product_name }}
                </td>
                <td valign="top" style="padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;">
                    {{ $item->product_price }}
                </td>
                <td valign="top" style="padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;">
                    {{ $item->product_quantity }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<br>
<br>
Thanks,
<br>
{{ config('app.name') }} team
@endcomponent
