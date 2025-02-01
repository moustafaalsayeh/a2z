<?php

namespace Modules\Address\Helpers;

use Modules\Address\Entities\Address;

trait Addressable
{
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable')
            ->select([
                'id',
                'addressable_type',
                'addressable_id',
                'country_id',
                'title',
                'address_details',
                'state',
                'city_id',
                'postal_code',
                'street',
                'building',
                'apartment',
                'flat',
                'landmark',
                'phone',
                'mobile',
                'lat',
                'lng',
                'is_primary',
                'is_shipping',
            ]);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable')
            ->select([
                'id',
                'addressable_type',
                'addressable_id',
                'country_id',
                'title',
                'address_details',
                'state',
                'city_id',
                'postal_code',
                'street',
                'building',
                'apartment',
                'flat',
                'landmark',
                'phone',
                'mobile',
                'lat',
                'lng',
                'is_primary',
                'is_shipping',
            ]);
    }

    public function addAddress($data)
    {
        $keys = array_keys($data);
        if (in_array('is_primary', $keys) && $data['is_primary'])
        {
            $this->normalizePreviousPrimaryAddress();
        }
        if (in_array('is_shipping', $keys) && $data['is_shipping'])
        {
            $this->normalizePreviousShippingAddress();
        }
        return $this->address()->create([
            'country_id' => in_array('country_id', $keys) ? $data['country_id'] : null,
            'title' => in_array('title', $keys) ? $data['title'] : null,
            'address_details' => in_array('address_details', $keys) ? $data['address_details'] : null,
            'city_id' => in_array('city_id', $keys) ? $data['city_id'] : null,
            'state' => in_array('state', $keys) ? $data['state'] : null,
            'postal_code' => in_array('postal_code', $keys) ? $data['postal_code'] : null,
            'street' => in_array('street', $keys) ? $data['street'] : null,
            'building' => in_array('building', $keys) ? $data['building'] : null,
            'apartment' => in_array('apartment', $keys) ? $data['apartment'] : null,
            'landmark' => in_array('landmark', $keys) ? $data['landmark'] : null,
            'phone' => in_array('phone', $keys) ? $data['phone'] : null,
            'mobile' => in_array('mobile', $keys) ? $data['mobile'] : null,
            'flat' => in_array('floor', $keys) ? $data['floor'] : null,
            'lat' => in_array('lat', $keys) ? $data['lat'] : null,
            'lng' => in_array('lng', $keys) ? $data['lng'] : null,
            'is_primary' => in_array('is_primary', $keys) ? $data['is_primary'] : false,
            'is_shipping' => in_array('is_shipping', $keys) ? $data['is_shipping'] : false,
        ]);
        return $this;
    }

    public function editAddress(Address $address, $data)
    {
        $keys = array_keys($data);
        if(in_array('is_primary', $keys) && $data['is_primary'])
        {
            $this->normalizePreviousPrimaryAddress();
        }
        if(in_array('is_shipping', $keys) && $data['is_shipping'])
        {
            $this->normalizePreviousShippingAddress();
        }
        $address->refresh();
        return $address->update([
            'country_id' => in_array('country_id', $keys) ? $data['country_id'] : $address->country_id,
            'city_id' => in_array('city_id', $keys) ? $data['city_id'] : $address->city_id,
            'title' => in_array('title', $keys) ? $data['title'] : $address->title,
            'address_details' => in_array('address_details', $keys) ? $data['address_details'] : $address->address_details,
            'state' => in_array('state', $keys) ? $data['state'] : $address->state,
            'postal_code' => in_array('postal_code', $keys) ? $data['postal_code'] : $address->postal_code,
            'street' => in_array('street', $keys) ? $data['street'] : $address->street,
            'building' => in_array('building', $keys) ? $data['building'] : $address->building,
            'apartment' => in_array('apartment', $keys) ? $data['apartment'] : $address->apartment,
            'landmark' => in_array('landmark', $keys) ? $data['landmark'] : $address->landmark,
            'phone' => in_array('phone', $keys) ? $data['phone'] : $address->phone,
            'mobile' => in_array('mobile', $keys) ? $data['mobile'] : $address->mobile,
            'flat' => in_array('floor', $keys) ? $data['floor'] : $address->flat,
            'lat' => in_array('lat', $keys) ? $data['lat'] : $address->lat,
            'lng' => in_array('lng', $keys) ? $data['lng'] : $address->lng,
            'is_primary' => in_array('is_primary', $keys) ? $data['is_primary'] : $address->is_primary,
            'is_shipping' => in_array('is_shipping', $keys) ? $data['is_shipping'] : $address->is_shipping,
        ]);
        return $this;
    }

    public function ownsAddress(Address $address)
    {
        return in_array($address->id, $this->addresses()->select('id')->get()->pluck('id')->toArray());
    }

    public function getPrimaryAddressAttribute()
    {
        return $this->addresses()->where('is_primary', 1)->first();
    }

    public function getShippingAddressAttribute()
    {
        return $this->addresses()->where('is_shipping', 1)->first();
    }

    public function normalizePreviousPrimaryAddress()
    {
        if($primary_address = $this->primary_address)
        {
            $primary_address->is_primary = 0;
            $primary_address->save();
        }
    }

    public function normalizePreviousShippingAddress()
    {
        if ($shipping_address = $this->shipping_address) {
            $shipping_address->is_shipping = 0;
            $shipping_address->save();
        }
    }
}
