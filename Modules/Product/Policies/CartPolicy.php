<?php

namespace Modules\Product\Policies;

use Modules\APIAuth\Entities\User;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\CartItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
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
        return $user->can('view_carts');
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->type == 'buyer';
    }
    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, Cart $cart)
    {
        return $user->can('delete_cart')
            || ($cart->user && $cart->user->id == $user->id);
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function updateItem(User $user, Cart $cart)
    {
        return $user->can('update_cart_item')
            || (($cart->user && $cart->user->id == $user->id));
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function deleteItem(User $user, Cart $cart)
    {
        return $user->can('delete_cart_item')
            || (($cart->user && $cart->user->id == $user->id));
    }
}
