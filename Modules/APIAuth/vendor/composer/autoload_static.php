<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit647da6f6252a1afb5fce8dd7aa2ad6fd
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\APIAuth\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\APIAuth\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Modules\\APIAuth\\Database\\Seeders\\APIAuthDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/APIAuthDatabaseSeeder.php',
        'Modules\\APIAuth\\Emails\\InviteUserMail' => __DIR__ . '/../..' . '/Emails/InviteUserMail.php',
        'Modules\\APIAuth\\Emails\\ResetPasswordEmail' => __DIR__ . '/../..' . '/Emails/ResetPasswordEmail.php',
        'Modules\\APIAuth\\Emails\\VerifyMail' => __DIR__ . '/../..' . '/Emails/VerifyMail.php',
        'Modules\\APIAuth\\Entities\\User' => __DIR__ . '/../..' . '/Entities/User.php',
        'Modules\\APIAuth\\Helpers\\Helpers' => __DIR__ . '/../..' . '/Helpers/Helpers.php',
        'Modules\\APIAuth\\Http\\Controllers\\AuthController' => __DIR__ . '/../..' . '/Http/Controllers/AuthController.php',
        'Modules\\APIAuth\\Http\\Controllers\\UserController' => __DIR__ . '/../..' . '/Http/Controllers/UserController.php',
        'Modules\\APIAuth\\Http\\Requests\\LoginRequest' => __DIR__ . '/../..' . '/Http/Requests/LoginRequest.php',
        'Modules\\APIAuth\\Http\\Requests\\RegisterQueueRequest' => __DIR__ . '/../..' . '/Http/Requests/RegisterQueueRequest.php',
        'Modules\\APIAuth\\Http\\Requests\\ResetPasswordEmailRequest' => __DIR__ . '/../..' . '/Http/Requests/ResetPasswordEmailRequest.php',
        'Modules\\APIAuth\\Http\\Requests\\ResetPasswordRequest' => __DIR__ . '/../..' . '/Http/Requests/ResetPasswordRequest.php',
        'Modules\\APIAuth\\Http\\Requests\\UpdateUserRequest' => __DIR__ . '/../..' . '/Http/Requests/UpdateUserRequest.php',
        'Modules\\APIAuth\\Jobs\\SendVerifyEmailJob' => __DIR__ . '/../..' . '/Jobs/SendVerifyEmailJob.php',
        'Modules\\APIAuth\\Providers\\APIAuthServiceProvider' => __DIR__ . '/../..' . '/Providers/APIAuthServiceProvider.php',
        'Modules\\APIAuth\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
        'Modules\\APIAuth\\Transformers\\UserResource' => __DIR__ . '/../..' . '/Transformers/UserResource.php',
        'Modules\\APIAuth\\Transformers\\UserSimpleResource' => __DIR__ . '/../..' . '/Transformers/UserSimpleResource.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit647da6f6252a1afb5fce8dd7aa2ad6fd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit647da6f6252a1afb5fce8dd7aa2ad6fd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit647da6f6252a1afb5fce8dd7aa2ad6fd::$classMap;

        }, null, ClassLoader::class);
    }
}
