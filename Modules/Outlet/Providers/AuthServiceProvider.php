<?php

namespace Modules\Outlet\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;
use Modules\Outlet\Entities\DeliveryArea;
use Modules\Outlet\Entities\Outlet;
use Modules\Outlet\Policies\DeliveryAreaPolicy;
use Modules\Outlet\Policies\OutletPolicy;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        Outlet::class => OutletPolicy::class,
        DeliveryArea::class => DeliveryAreaPolicy::class
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
