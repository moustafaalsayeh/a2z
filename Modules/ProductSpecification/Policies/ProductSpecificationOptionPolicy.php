<?php

namespace Modules\ProductSpecification\Policies;

use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ProductSpecification\Entities\ProductSpecificationOption;

class ProductSpecificationOptionPolicy
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
        return $user->can('create_product_specification_option') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, ProductSpecificationOption $product_specification_option)
    {
        if($user->can('update_product_specification_option'))
        {
            return true;
        }
        if($product_specification_option->productSpecification->specificable_type == Product::class && $product_specification_option->productSpecification->products->first()->outlet->user->id == $user->id)
        {
            return true;
        }
        if($product_specification_option->productSpecification->specificable_type == Outlet::class && $product_specification_option->productSpecification->outlets->first()->user->id == $user->id)
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
    public function delete(User $user, ProductSpecificationOption $product_specification_option)
    {
        if($user->can('delete_product_specification_option'))
        {
            return true;
        }
        if($product_specification_option->productSpecification->products->first() && $product_specification_option->productSpecification->products->first()->outlet->user->id == $user->id)
        {
            return true;
        }
        if($product_specification_option->productSpecification->outlets->first() && $product_specification_option->productSpecification->outlets->first()->user->id == $user->id)
        {
            return true;
        }
        return false;
    }
}
