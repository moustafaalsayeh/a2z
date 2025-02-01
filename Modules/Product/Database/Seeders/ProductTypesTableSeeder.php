<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductType;

class ProductTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $original_product_type = ProductType::create([
        //     'position' => 100
        // ]);
        // $original_product_type->translate([
        //     'name' => 'Default product type',
        //     'description' => 'The default product type that products are set to when its parent product type deleted'
        // ]);

        $faker = \Faker\Factory::create();
        $faker_ar = \Faker\Factory::create('ar_SA');

        for ($i = 0; $i < 5; $i++) {
            $product_type = ProductType::create([
                'position' => $i
            ]);
            $product_type->translate([
                'name' => $faker->lastName,
                'description' => $faker->sentence(15)
            ]);
            $product_type->translate([
                'name' => $faker_ar->lastName,
                'description' => $faker_ar->sentence(15)
            ], 'ar');
            $product_type->addMedia(
                    'photo',
                    'public/photos/producttypes/product_type.png',
                    'public/photos/producttypes/product_type_thumb_.png',
                    'public/photos/producttypes/product_type_meduim_.png',
                    'public/photos/producttypes/product_type_large_.png',
                    'logo',
                    'this is the logo image',
                    1
                );

            for ($j=0; $j < 3; $j++) {
                $sub_product_type = ProductType::create([
                    'product_type_id' => $product_type->id,
                    'position' => $j,
                ]);
                $sub_product_type->translate([
                    'name' => $faker->lastName,
                    'description' => $faker->sentence(15)
                ]);
                $sub_product_type->translate([
                    'name' => $faker_ar->lastName,
                    'description' => $faker_ar->sentence(15)
                ], 'ar');
                $sub_product_type->addMedia(
                    'photo',
                    'public/photos/producttypes/product_type.png',
                    'public/photos/producttypes/product_type_thumb_.png',
                    'public/photos/producttypes/product_type_meduim_.png',
                    'public/photos/producttypes/product_type_large_.png',
                    'logo',
                    'this is the logo image',
                    1
                );

                for ($k = 0; $k < 2; $k++) {
                    $sub_sub_product_type = ProductType::create([
                        'product_type_id' => $sub_product_type->id,
                        'position' => $k,
                    ]);
                    $sub_sub_product_type->translate([
                        'name' => $faker->lastName,
                        'description' => $faker->sentence(15)
                    ]);
                    $sub_sub_product_type->translate([
                        'name' => $faker_ar->lastName,
                        'description' => $faker_ar->sentence(15)
                    ], 'ar');
                    $sub_sub_product_type->addMedia(
                    'photo',
                    'public/photos/producttypes/product_type.png',
                    'public/photos/producttypes/product_type_thumb_.png',
                    'public/photos/producttypes/product_type_meduim_.png',
                    'public/photos/producttypes/product_type_large_.png',
                    'logo',
                    'this is the logo image',
                    1
                );
                }
            }
        }

    }
}
