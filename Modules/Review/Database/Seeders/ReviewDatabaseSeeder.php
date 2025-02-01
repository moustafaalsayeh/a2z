<?php

namespace Modules\Review\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\APIAuth\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class ReviewDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $faker = \Faker\Factory::create();

        $buyer_id = User::where('username', 'buyer')->firstOrFail()->id;

        $products = Product::get();

        $odd = 1;

        foreach ($products as $product) {
            if($odd)
            {
                // dd($product->reviewItems);
                $review_rates = [];
                for ($i=0; $i < 3; $i++) {
                    $review_item = $product->reviewItems()->create([
                        'reviewable_type' => Product::class,
                        'reviewable_id' => $product->id
                    ]);
                    $review_item->translate(['title' => $faker->sentence(2)], 'en');

                    $review_rates[] = [
                        'reviewable_id' => $review_item->id,
                        'rate' => $faker->randomElement([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5])
                    ];
                }
                // $product->reviewItems()->createMany([
                //     [
                //         'title' => $faker->sentence(2)
                //     ],
                //     [
                //         'title' => $faker->sentence(2)
                //     ],
                //     [
                //         'title' => $faker->sentence(2)
                //     ],
                // ]);

                $review = $product->reviews()->create([
                    'user_id' => $buyer_id,
                    'comment' => $faker->sentence(15)
                ]);

                $review->rates()->createMany($review_rates);

// dd("S");
                // foreach ($product->reviewItems as $review_item) {
                //     $review->rates()->create([
                //         'reviewable_id' => $review_item->id,
                //         'rate' => $faker->randomElement([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5])
                //     ]);
                // }
            }
        }
    }
}
