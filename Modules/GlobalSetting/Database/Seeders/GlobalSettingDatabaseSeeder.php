<?php

namespace Modules\GlobalSetting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\GlobalSetting\Entities\GlobalSetting;

class GlobalSettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $settings = [
            [
                'name' => 'default currency',
                'value' => 'usd',
                'type' => 'string'
            ],
            [
                'name' => 'default locale',
                'value' => 'en',
                'type' => 'string'
            ],
            [
                'name' => 'default timezone',
                'value' => 'UTC',
                'type' => 'string'
            ],
        ];

        foreach ($settings as $key => $setting) {
            GlobalSetting::create($setting);
        }

        // $this->call("OthersTableSeeder");
    }
}