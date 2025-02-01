<?php

namespace Modules\ProductSpecification\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductType;
use Modules\ProductSpecification\Entities\ProductSpecification;
use Modules\ProductSpecification\Entities\ProductSpecificationOption;

class ProductSpecificationDatabaseSeeder extends Seeder
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

        // add all 3 types of product specs
        // to first product type's product
        $product_type_products = ProductType::findOrFail(3)->products;
        // dd($product_type_products->count());
        foreach ($product_type_products as $key => $product) {
            $specs = [];
            // text spec
            $specs[] = $product->productSpecifications()->create(
                factory(ProductSpecification::class)
                ->make(['type' => 'text'])
                ->toArray()
            );

            // radio spec
            $specs[] = $product->productSpecifications()->create(
                factory(ProductSpecification::class)
                ->make(['type' => 'radio'])
                ->toArray()
            );

            // checkbox spec
            $specs[] = $product->productSpecifications()->create(
                factory(ProductSpecification::class)
                ->make(['type' => 'checkbox'])
                ->toArray()
            );

            foreach ($specs as $key => $spec) {
                $spec->translate([
                    'title' => $faker->sentence(2),
                    'hint' => $faker->sentence(5),
                ], 'en');

                if($spec->type != 'text')
                {
                    $options = $spec->options()->createMany(factory(ProductSpecificationOption::class, 3)
                        ->make(['prod_spec_id' => $spec->id])
                        ->toArray()
                    );
                    foreach ($options as $spec_option) {
                        $spec_option->translate([
                            'value' => $faker->sentence(2),
                        ], 'en');
                    }
                }
            }
        }

        // create random Specs for Products
        $products = Product::get();
        $odd = 1;
        foreach ($products as $key => $product) {
            if($odd && $product->productSpecifications->count() == 0)
            {
                $specs = $product->productSpecifications()->createMany(
                    factory(ProductSpecification::class, 2)
                    ->make()
                    ->toArray()
                );
                foreach ($specs as $key => $spec) {
                    $spec->translate([
                        'title' => $faker->sentence(2),
                        'hint' => $faker->sentence(5),
                    ], 'en');

                    if($spec->type != 'text')
                    {
                        $options = $spec->options()->createMany(factory(ProductSpecificationOption::class, 3)
                            ->make(['prod_spec_id' => $spec->id])
                            ->toArray()
                        );
                        foreach ($options as $spec_option) {
                            $spec_option->translate([
                                'value' => $faker->sentence(2),
                            ], 'en');
                        }
                    }
                }
            }
            $odd = !$odd;
        }

        // create Specs for Outlets
        $outlets = Outlet::get();
        $odd = 1;
        foreach ($outlets as $key => $outlet) {
            if($odd)
            {
                $spec = $outlet->productSpecifications()->create(
                    factory(ProductSpecification::class)
                    ->make()
                    ->toArray()
                );
                $spec->translate([
                    'title' => $faker->sentence(2),
                    'hint' => $faker->sentence(5),
                ], 'en');

                if($spec->type != 'text')
                {
                    $options = $spec->options()->createMany(factory(ProductSpecificationOption::class, 3)
                        ->make(['prod_spec_id' => $spec->id])
                        ->toArray()
                    );
                    foreach ($options as $spec_option) {
                        $spec_option->translate([
                            'value' => $faker->sentence(2),
                        ], 'en');
                    }
                }
            }
            $odd = !$odd;
        }

        // $this->call("OthersTableSeeder");
    }
}
