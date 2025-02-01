<?php

namespace Modules\APIAuth\Helpers;

use NumberFormatter;
use Twilio\Rest\Client;
use Swap\Laravel\Facades\Swap;
use Illuminate\Support\Collection;
use Modules\APIAuth\Entities\User;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Modules\APIAuth\Emails\VerifyMail;
use Modules\Currency\Entities\Currency;
use Modules\APIAuth\Jobs\SendVerifyEmailJob;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Currency\Transformers\CurrencyResource;
use Modules\GlobalSetting\Entities\GlobalSetting;

class Helpers
{
    public static function sendVerifyEmail($email, $verify_token)
    {
        // dispatch(new SendVerifyEmailJob($email, $verify_token));

        try {
            $url = url('api/verify-email') . '/' . $email . '/' . $verify_token;

            Mail::to($email)->send(new VerifyMail($url, $verify_token));

            return true;
        }
        catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Sends sms to user using Twilio's programmable sms client
     * @param String $message Body of sms
     * @param Number $recipients string or array of phone number of recepient
     */
    public static function sendMessage($message, $rec)
    {
        try {
            $account_sid = config('app.twilio_sid');
            $auth_token = config('app.twilio_auth_token');
            $twilio_number = config('app.twilio_number');

            $client = new Client($account_sid, $auth_token);

            $client->messages->create($rec, ['from' => $twilio_number, 'body' => $message]);

            return true;
        }
        catch (\Throwable $th) {
            return false;
        }
    }

    public function moneyGetter($value)
    {
        $money =  $value / 100;

        $currency = $this->getCurrency();

        if($currency->code != 'USD')
        {
            $rate = Swap::latest('USD/' . $currency->code);

            $money *= $rate->getValue();
        }

        $money = round((float) $money, 2);

        return $money;
    }

    public static function moneySetter($value)
    {
        $value = $value * 100;
        return (int) floor($value);
    }

    public static function getCurrency()
    {
        $default_currency = GlobalSetting::where('name', 'default currency')->first();
        $default_currency = $default_currency ? $default_currency->value : 'usd';
        $currency_header = app('request')->header('currency', $default_currency);

        $currency = Currency::where('code', 'like', $currency_header)->firstOrFail();

        return $currency;
    }

    public static function getLocale()
    {
        return in_array(request()->header('X-locale'), config('translatable.locales')) ? request()->header('X-locale') : config('translatable.fallback_locale');
    }

    public static function addPhotosToModel($model, $photos)
    {
        foreach ($photos as $photo)
        {
            $photo_title = array_key_exists('title', $photo) ? strtolower($photo['title']) : null;
            $photo_description = array_key_exists('description', $photo) ? $photo['description'] : null;
            $photo_is_main = array_key_exists('is_main', $photo) ? $photo['is_main'] : null;

            if($photo_title == 'logo' && $model->logo_media)
            {
                $model->deleteMedia($model->logo_media);
            }
            else if($photo_title == 'cover' && $cover_media = $model->media()->where('title', 'cover')->first())
            {
                $model->deleteMedia($cover_media);
            }
            else if ($photo_title == 'profile' && $profile_media = $model->media()->where('title', 'profile')->first())
            {
                $model->deleteMedia($profile_media);
            }

            $model->addMediaByType('photo', $photo['image'], null, $photo_title, $photo_description, $photo_is_main);
        }
    }

    /* is (lon, lat) inside the polygon $p?
    * use ray casting algorithm (http://en.wikipedia.org/wiki/Point_in_polygon)
    * ie. project a horizontal line from our point to each segment
    * code adapted from http://stackoverflow.com/questions/14149099/raycasting-algorithm-with-gps-coordinates
    */
    public static function insidePolygon($test_point, $points)
    {
        $p0 = end($points);
        $ctr = 0;
        foreach ($points as $p1) {

            // there is a bug with this algorithm, when a point in "on" a vertex
            // in that case just add an epsilon
            if ($test_point[1] == $p0[1])
                $test_point[1] += 0.0000000001; #epsilon

            // ignore edges of constant latitude (yes, this is correct!)
            if ($p0[1] != $p1[1]) {
                // scale latitude of $test_point so that $p0 maps to 0 and $p1 to 1:
                $interp = ($test_point[1] - $p0[1]) / ($p1[1] - $p0[1]);

                // does the edge intersect the latitude of $test_point?
                // (note: use >= and < to avoid double-counting exact endpoint hits)
                if ($interp >= 0 && $interp < 1) {
                    // longitude of the edge at the latitude of the test point:
                    // (could use fancy spherical interpolation here, but for small
                    // regions linear interpolation should be fine)
                    $long = $interp * $p1[0] + (1 - $interp) * $p0[0];
                    // is the intersection east of the test point?
                    if ($long > $test_point[0]) {
                        // if so, count it:
                        $ctr++;
                        #echo "YES &$test_point[0],$test_point[1] ($p0[0],$p0[1])x($p1[0],$p1[1]) ; $interp,$long","\n";
                    }
                }
            }
            $p0 = $p1;
        }
        return ($ctr & 1);
    }


    public static function paginate(Collection $results, $pageSize)
    {
        $page = Paginator::resolveCurrentPage('page');

        return self::paginator($results->forPage($page, $pageSize), $results->count(), $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }

    public static function getOnlyOutletsDelivering($outlets)
    {
        if (!(auth('api')->user() && auth('api')->user()->primary_address) && !request()->query('location')) {
            return $outlets;
        }

        $location = Helpers::getDeliveryLocation();

        return $outlets->map(function ($outlet) use ($location) {
            foreach ($outlet->deliveryAreas as $key => $area) {
                if (Helpers::insidePolygon($location, $area['points'])) {
                    return $outlet;
                }
            }
            return null;
        })->reject(function ($oulet) {
            return empty($oulet);
        });
    }

    public static function getOnlyProductssDelivering($products)
    {
        if (!(auth('api')->user() && auth('api')->user()->primary_address) && !request()->query('location')) {
            return $products;
        }

        $location = Helpers::getDeliveryLocation();

        return $products->map(function ($product) use ($location) {
            foreach ($product->outlet->deliveryAreas as $key => $area) {
                if (Helpers::insidePolygon($location, $area['points'])) {
                    return $product;
                }
            }
            return null;
        })->reject(function ($product) {
            return empty($product);
        });
    }

    public static function getDeliveryLocation()
    {
        return request()->query('location') ?
            explode(',', request()->query('location')) :
            [auth('api')->user()->primary_address->lat, auth('api')->user()->primary_address->lng];

    }
}
