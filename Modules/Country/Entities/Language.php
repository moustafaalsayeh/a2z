<?php

namespace Modules\Country\Entities;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all the languages from the JSON file.
     *
     * @return array
     */
    public static function allJSON()
    {
        $route = dirname(dirname(__FILE__)) . '/Database/data/languages.json';

        return json_decode(file_get_contents($route), true);
    }
}
