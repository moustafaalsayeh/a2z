<?php

namespace Modules\Order\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;
use Modules\Order\Entities\Order;
use Modules\Order\Policies\OrderPolicy;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        Order::class => OrderPolicy::class
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
