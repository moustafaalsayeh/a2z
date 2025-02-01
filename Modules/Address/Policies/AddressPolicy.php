<?php

namespace Modules\Address\Policies;

use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\Address\Entities\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any Addresss.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('view_addresses');
    }

    /**
     * Determine whether the user can create Addresss.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the Address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $Address
     * @return mixed
     */
    public function update(User $user, Address $address)
    {
        if (
            ($address->addressable == User::class && $address->addressable->id == $user->id)
            ||
            ($address->addressable == Outlet::class && $address->addressable->user->id == $user->id)
            ||
            ($user->type == 'super' && $user->can('update_address'))
        )
            return true;

        return false;
    }

    /**
     * Determine whether the user can delete the Address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $Address
     * @return mixed
     */
    public function delete(User $user, Address $address)
    {
        return $user->can('delete_address')
            || ($cart->user && $cart->user->id == $user->id);
    }
}
