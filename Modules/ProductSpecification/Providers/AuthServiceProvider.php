<?php

namespace Modules\ProductSpecification\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;
use Modules\ProductSpecification\Entities\ProductSpecification;
use Modules\ProductSpecification\Entities\ProductSpecificationOption;
use Modules\ProductSpecification\Policies\ProductSpecificationOptionPolicy;
use Modules\ProductSpecification\Policies\ProductSpecificationPolicy;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        ProductSpecification::class => ProductSpecificationPolicy::class,
        ProductSpecificationOption::class => ProductSpecificationOptionPolicy::class
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
