<?php

namespace Modules\APISocialLogin\Entities;

use Modules\APIAuth\Entities\User;
use Illuminate\Database\Eloquent\Model;

class SocialProviderUser extends Model
{
    protected $table = 'social_provider_users';

    public $timestamps = false;

    protected $fillable = [
        'provider',
        'provider_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}