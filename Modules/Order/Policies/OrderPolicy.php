<?php

namespace Modules\Order\Policies;

use Modules\APIAuth\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Order\Entities\Order;
use Modules\Outlet\Entities\Outlet;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('view_orders');
    }

    public function view(User $user, Order $order)
    {
        return $user->can('view_orders')
            || ($order->user_id && $order->user_id == $user->id)
            || ($order->outlet && $order->outlet->user->id == $user->id)
            || $user->type == 'delivery';
    }

    public function viewBuyer(User $user)
    {
        return $user->type == 'buyer';
    }

    public function viewDelivery(User $user)
    {
        return $user->type == 'delivery';
    }

    public function viewSeller(User $user)
    {
        return $user->type == 'seller';
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->type == 'buyer' && $user->primary_address;
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        // Buyer can only update status and make it cancelled only
        if($user->type == 'buyer')
        {
            return true;
        }
        else if($user->type == 'seller' && $order->outlet->user->id == $user->id)
        {
            return true;
        }
        else if($user->type == 'admin' && $user->can('update_order'))
        {
            return true;
        }
        else if($user->type == 'delivery')
        {
            return true;
        }

        return false;
    }

    public function reorder(User $user, Order $order)
    {
        return $user->type == 'buyer' && $order->user->id == $user->id;
    }

    public function delete(User $user, Order $order)
    {
        return $user->can('delete_order');
    }

}
