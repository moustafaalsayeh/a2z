<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Currency\Transformers\CurrencyResource;

class GlobalDataController extends Controller
{
    public function index(Request $request)
    {
        $data['currency'] = new CurrencyResource(Helpers::getCurrency());

        return response([
            'message' => __('success_action', ['model' => __('global_data'), 'action' => __('updated')]),
            'data' => $data
        ]);
    }
}
