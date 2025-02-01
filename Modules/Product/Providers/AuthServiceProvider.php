<?php

namespace Modules\Product\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductType;
use Modules\Product\Policies\CartPolicy;
use Modules\Product\Policies\ProductPolicy;
use Modules\Product\Policies\ProductTypePolicy;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        ProductType::class => ProductTypePolicy::class,
        Cart::class => CartPolicy::class
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
