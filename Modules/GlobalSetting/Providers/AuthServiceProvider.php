<?php

namespace Modules\GlobalSetting\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderAuthServiceProvider;
use Modules\GlobalSetting\Entities\GlobalSetting;
use Modules\GlobalSetting\Policies\GlobalSettingPolicy;

class AuthServiceProvider extends ProviderAuthServiceProvider
{
    protected $policies = [
        GlobalSetting::class => GlobalSettingPolicy::class
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
