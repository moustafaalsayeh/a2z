<?php

namespace Modules\Outlet\Policies;

use Modules\APIAuth\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Outlet\Entities\DeliveryArea;

class DeliveryAreaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view_delivery_area') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_delivery_area') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, DeliveryArea $delivery_area)
    {
        return $user->can('update_delivery_area') || $delivery_area->outlet->user->id == $user->id;
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, DeliveryArea $delivery_area)
    {
        return $user->can('delete_delivery_area') || $delivery_area->outlet->user->id == $user->id;
    }
}
