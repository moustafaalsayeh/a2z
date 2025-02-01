<?php

namespace Modules\Menu\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Menu\Entities\Menu;
use Modules\Outlet\Entities\Outlet;

class MenuDatabaseSeeder extends Seeder
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
        $outlets = Outlet::get();

        foreach ($outlets as $key => $outlet) {
            $outlet_products = $outlet->products->pluck('id');
            for($i = 0; $i<2; $i++)
            {
                $menu = Menu::create([
                    'outlet_id' => $outlet->id,
                ]);
                $menu->translate([
                    'name' => $faker->firstName,
                    'description' => $faker->sentence(25)
                ], 'en');
                $menu->save();
                $menu->products()->attach(
                    $faker->randomElements($outlet_products, 10)
                );
            }
        }

        // for ($i=0; $i < 5; $i++) {
        // }

        // $this->call("OthersTableSeeder");
    }
}
