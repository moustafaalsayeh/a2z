<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit091113af03b8f8475393b12345d2225f
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\APISocialLogin\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\APISocialLogin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Modules\\APISocialLogin\\Database\\Seeders\\APISocialLoginDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/APISocialLoginDatabaseSeeder.php',
        'Modules\\APISocialLogin\\Entities\\SocialProviderUser' => __DIR__ . '/../..' . '/Entities/SocialProviderUser.php',
        'Modules\\APISocialLogin\\Http\\Controllers\\APISocialLoginController' => __DIR__ . '/../..' . '/Http/Controllers/APISocialLoginController.php',
        'Modules\\APISocialLogin\\Http\\Controllers\\SocialLoginController' => __DIR__ . '/../..' . '/Http/Controllers/SocialLoginController.php',
        'Modules\\APISocialLogin\\Http\\Controllers\\SocialSignupController' => __DIR__ . '/../..' . '/Http/Controllers/SocialSignupController.php',
        'Modules\\APISocialLogin\\Http\\Requests\\SocialLoginRequest' => __DIR__ . '/../..' . '/Http/Requests/SocialLoginRequest.php',
        'Modules\\APISocialLogin\\Http\\Requests\\SocialSignupRequest' => __DIR__ . '/../..' . '/Http/Requests/SocialSignupRequest.php',
        'Modules\\APISocialLogin\\Providers\\APISocialLoginServiceProvider' => __DIR__ . '/../..' . '/Providers/APISocialLoginServiceProvider.php',
        'Modules\\APISocialLogin\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit091113af03b8f8475393b12345d2225f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit091113af03b8f8475393b12345d2225f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit091113af03b8f8475393b12345d2225f::$classMap;

        }, null, ClassLoader::class);
    }
}
