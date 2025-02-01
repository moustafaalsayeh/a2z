<?php

namespace Modules\Address\Providers;


use Modules\Address\Entities\Address;
use Modules\Address\Policies\AddressPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        Address::class => AddressPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
