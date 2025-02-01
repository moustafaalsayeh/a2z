<?php

namespace Modules\APIAuth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\APIAuth\Entities\User;
use Modules\Address\Entities\Address;
use Illuminate\Database\Eloquent\Model;

class APIAuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Superadmin account
        $user = User::create([
            'username' => 'super',
            'first_name' => 'super',
            'email' => 'super@homemade.com',
            'password' => 123456,
            'email_verified_at' => now(),
            'type' => 'admin',
            'language_id' => 1,
        ]);
        $user->addMedia(
            'photo',
            'public/photos/users/user.png',
            'public/photos/users/user_thumb_.png',
            'public/photos/users/user_meduim_.png',
            'public/photos/users/user_large_.png',
            'logo',
            'this is the logo image',
            1
        );

        // seller account
        $user = User::create([
            'username' => 'seller',
            'first_name' => 'seller name',
            'email' => 'seller@homemade.com',
            'password' => 123456,
            'email_verified_at' => now(),
            'type' => 'seller',
            'language_id' => 1,
        ]);
        factory(Address::class)->create([
            'addressable_type' => User::class,
            'addressable_id' => $user->id
        ]);
        $user->addMedia(
            'photo',
            'public/photos/users/user.png',
            'public/photos/users/user_thumb_.png',
            'public/photos/users/user_meduim_.png',
            'public/photos/users/user_large_.png',
            'logo',
            'this is the logo image',
            1
        );

        // buyer account
        $user = User::create([
            'username' => 'buyer',
            'first_name' => 'buyer name',
            'email' => 'buyer@homemade.com',
            'password' => 123456,
            'email_verified_at' => now(),
            'type' => 'buyer',
            'language_id' => 1,
        ]);
        factory(Address::class)->create([
            'addressable_type' => User::class,
            'addressable_id' => $user->id
        ]);
        $user->addMedia(
            'photo',
            'public/photos/users/user.png',
            'public/photos/users/user_thumb_.png',
            'public/photos/users/user_meduim_.png',
            'public/photos/users/user_large_.png',
            'logo',
            'this is the logo image',
            1
        );

        // delivery account
        $user = User::create([
            'username' => 'delivery',
            'first_name' => 'delivery name',
            'email' => 'delivery@homemade.com',
            'password' => 123456,
            'email_verified_at' => now(),
            'type' => 'delivery',
            'language_id' => 1,
        ]);
        $user->addMedia(
            'photo',
            'public/photos/users/user.png',
            'public/photos/users/user_thumb_.png',
            'public/photos/users/user_meduim_.png',
            'public/photos/users/user_large_.png',
            'logo',
            'this is the logo image',
            1
        );
    }
}
