<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductType;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ProductTypesTableSeeder::class);

        $faker = \Faker\Factory::create();

        $outlets = Outlet::get()->pluck('id');

        $product_types = ProductType::get();
        $product_types = $product_types->reject(function($product_type){
            return $product_type->is_leaf;
        });
        $product_types = $product_types->pluck('id');

        foreach ($outlets as $key => $outlet_id) {
            for ($i=0; $i < 100; $i++) {
                $product = Product::create([
                    'outlet_id' => $outlet_id,
                    'price' => $faker->numberBetween(10,200),
                    'product_type_id' => $faker->randomElement($product_types)
                ]);

                $product->translate(['name' => $faker->firstName, 'description' => $faker->sentence(10)]);

                $product->addMedia(
                    'photo',
                    'public/photos/products/product.png',
                    'public/photos/products/product_thumb_.png',
                    'public/photos/products/product_meduim_.png',
                    'public/photos/products/product_large_.png',
                    'logo',
                    'this is the logo image',
                    1
                );
            }
        }
    }
}
