<?php

namespace Modules\Country\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;
use Modules\Country\Entities\Country;
use Modules\Country\Entities\Language;
use Modules\Country\Policies\CountryPolicy;
use Modules\Country\Policies\LanguagePolicy;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        Country::class => CountryPolicy::class,
        Language::class => LanguagePolicy::class
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
