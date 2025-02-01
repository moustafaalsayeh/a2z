<?php

namespace Modules\Outlet\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Address\Entities\Address;
use Modules\Outlet\Entities\WorkHour;
use Illuminate\Database\Eloquent\Model;
use Modules\Outlet\Entities\DeliveryArea;

class OutletDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = \Faker\Factory::create();

        $user_id = User::where('type', 'seller')->firstOrFail()->id;

        for ($i=0; $i < 10; $i++) {
            $outlet = Outlet::create([
                'user_id' => $user_id,
                'email' => $faker->unique()->email,
                'email_verified_at' => now(),
                'phone' => $faker->unique()->e164PhoneNumber,
                'phone_verified_at' => now(),
                'rank' => $faker->numberBetween(1, 5)
            ]);

            $outlet->translate([
                'name' => $faker->lastName,
                'info' => $faker->sentence(25)
            ]);

            $outlet->addMedia(
                'photo',
                'public/photos/outlets/retail.png',
                'public/photos/outlets/retail_thumb_.png',
                'public/photos/outlets/retail_meduim_.png',
                'public/photos/outlets/retail_large_.png',
                'logo',
                'this is the logo image',
                1
            );

            factory(Address::class)->create([
                'addressable_type' => Outlet::class,
                'addressable_id' => $outlet->id
            ]);

            factory(WorkHour::class)->create([
                'outlet_id' => $outlet->id
            ]);

            factory(DeliveryArea::class)->create([
                'outlet_id' => $outlet->id
            ]);
        }
    }
}
