<?php

namespace Modules\Outlet\Policies;

use Modules\APIAuth\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Outlet\Entities\Outlet;

class OutletPolicy
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
        return $user->can('index_outlets');
    }

    /**
     * Determine whether the user can view the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function view(User $user, Outlet $outlet)
    {
        return $user->can('show_outlet') || $outlet->user->id == $user->id;
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_outlet') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, Outlet $outlet)
    {
        return $user->can('update_outlet') || $outlet->user->id == $user->id;
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, Outlet $outlet)
    {
        return $user->can('delete_outlet') || $outlet->user->id == $user->id;
    }
}
