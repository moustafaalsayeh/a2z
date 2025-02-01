<?php

namespace Modules\Review\Policies;

use Modules\APIAuth\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Review\Entities\Reviewable;

class ReviewablePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_review_item') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, Reviewable $review_item)
    {
        return $user->can('update_review_item') || $user->type == 'seller';
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, Reviewable $review_item)
    {
        return $user->can('delete_review_item') || $user->type == 'seller';
    }
}
