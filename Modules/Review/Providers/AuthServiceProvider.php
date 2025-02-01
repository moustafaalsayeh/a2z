<?php

namespace Modules\Review\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;
use Modules\Review\Entities\Reviewable;
use Modules\Review\Policies\ReviewablePolicy;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        Reviewable::class => ReviewablePolicy::class
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
