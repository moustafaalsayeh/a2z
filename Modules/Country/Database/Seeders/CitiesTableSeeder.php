<?php

namespace Modules\Country\Database\Seeders;

use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Modules\Country\Entities\Country;
use Illuminate\Database\Eloquent\Model;
use Modules\Country\Entities\City;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $client = new Client();
        $all_countries =  $client->get('http://api.geonames.org/countryInfoJSON?formatted=true&username=saad539&style=full');
        $all_countries = json_decode($all_countries->getBody()->getContents());
// dd($all_countries->geonames);
        foreach ($all_countries->geonames as $key => $country) {
            $country_id = Country::where('alpha2code', $country->countryCode)->first()->id;

            $country_cities = $client->get('http://api.geonames.org/childrenJSON?formatted=true&geonameId='.$country->geonameId.'&username=saad539&style=full');
            $country_cities = json_decode($country_cities->getBody()->getContents());

            if(property_exists($country_cities, 'geonames'))
            {
                foreach ($country_cities->geonames as $key => $city) {
                    City::create([
                        'country_id' => $country_id,
                        'name' => $city->name,
                        'timezone' => property_exists($city, 'timezone') ? $city->timezone->timeZoneId : null,
                        'alternateNames' => property_exists($city, 'alternateNames') ? json_encode($city->alternateNames) : null ,
                    ]);
                }

            }
        }
    }
}
