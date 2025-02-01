<?php

namespace Modules\ProductSpecification\Policies;

use Modules\APIAuth\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Product\Entities\Product;
use Modules\ProductSpecification\Entities\ProductSpecification;

class ProductSpecificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_product_specification') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, ProductSpecification $product_specification)
    {
        if($user->can('update_product_specification'))
        {
            return true;
        }
        if($product_specification->outlets->first() && $product_specification->outlets->first()->user->id == $user->id)
        {
            return true;
        }
        if($product_specification->products->first() && $product_specification->products->first()->outlet->user->id == $user->id)
        {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, ProductSpecification $product_specification)
    {
        if($user->can('delete_product_specification'))
        {
            return true;
        }
        if($product_specification->outlets->first() && $product_specification->outlets->first()->user->id == $user->id)
        {
            return true;
        }
        if($product_specification->products->first() && $product_specification->products->first()->outlet->user->id == $user->id)
        {
            return true;
        }
        return false;
    }
}
