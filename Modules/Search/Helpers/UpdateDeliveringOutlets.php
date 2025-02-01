<?php

namespace Modules\Search\Helpers;

use Carbon\Carbon;
use App\Models\Promotion;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\APIAuth\Helpers\Helpers;

class UpdateDeliveringOutlets
{
    public function __invoke()
    {
        $buyers = User::where('type', 'buyer')->get();

        foreach ($buyers as $buyer) {
            if($buyer->primary_address)
            {
                $location = [$buyer->primary_address->lat, $buyer->primary_address->lng];
                $outlets = Outlet::get();
                $outlets = $outlets->map(function ($outlet) use ($location) {
                    foreach ($outlet->deliveryAreas as $key => $area) {
                        if (Helpers::insidePolygon($location, $area['points'])) {
                            return $outlet;
                            // dd($outlet);
                        }
                    }
                    return null;
                })->reject(function ($oulet) {
                    return empty($oulet);
                });

                $buyer->deliveringOutlets()->create([
                    'outlets' => $outlets->pluck('id')
                ]);
                
            }
        }
    }
}