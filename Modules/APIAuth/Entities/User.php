<?php

namespace Modules\APIAuth\Entities;

use Laravel\Cashier\Billable;
use Modules\Saves\Entities\Save;
use Modules\Order\Entities\Order;
use Laravel\Passport\HasApiTokens;
use Modules\Country\Entities\City;
use Modules\Product\Entities\Cart;
use Modules\Media\Helpers\Mediable;
use Modules\Outlet\Entities\Outlet;
use Modules\Review\Entities\Review;
use Illuminate\Auth\Authenticatable;
use Modules\Country\Entities\Country;
use Modules\Country\Entities\Language;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Modules\Currency\Entities\Currency;
use Illuminate\Notifications\Notifiable;
use Modules\Address\Helpers\Addressable;
use Modules\CustomFields\Helpers\Textable;
use Modules\Saves\Entities\SaveCollection;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Modules\APISocialLogin\Entities\SocialProviderUser;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Modules\Search\Entities\BuyerDeliveringOutlet;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Notifiable, HasApiTokens, Authenticatable, Authorizable, Textable, Mediable, Addressable, HasRoles, Billable;

    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'birthdate' => 'datetime',
        'gender' => 'integer',
        'is_blocked' => 'boolean'
    ];

    public function socialProviders()
    {
        return $this->hasMany(SocialProviderUser::class);
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    // [Mutators] //
    protected function setPasswordAttribute($value)
    {
        if ($value != '' && isset($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function saveCollections()
    {
        return $this->hasMany(SaveCollection::class);
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveringOutlets()
    {
        return $this->hasOne(BuyerDeliveringOutlet::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleteAllMedia();
            $model->addresses()->delete();
            $model->saves()->delete();
        });
    }

}
