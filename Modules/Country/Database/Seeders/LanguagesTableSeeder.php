<?php

namespace Modules\Country\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Country\Entities\Language;
use Illuminate\Database\Eloquent\Model;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Empty the table
        Language::truncate();

        // Get all from the JSON file
        $JSON_languages = Language::allJSON();

        foreach ($JSON_languages as $language) {
            Language::create([
                'alpha3code'    => ((isset($language['alpha3'])) ? $language['alpha3'] : null),
                'name'   => ((isset($language['english'])) ? $language['english'] : null),
            ]);
        }
    }
}
