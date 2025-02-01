<?php

namespace Modules\Product\Policies;

use Modules\APIAuth\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Product\Entities\Product;

class ProductPolicy
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
        return $user->can('index_products');
    }

    /**
     * Determine whether the user can view the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function view(User $user, Product $product)
    {
        return $user->can('show_product') || $product->outlet->user->id == $user->id;
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_product') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
        return $user->can('update_product') || $product->outlet->user->id == $user->id;
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        return $user->can('delete_product') || $product->outlet->user->id == $user->id;
    }
}
