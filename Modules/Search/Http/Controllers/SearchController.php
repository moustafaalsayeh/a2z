<?php

namespace Modules\Search\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Outlet\Entities\Outlet;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductType;
use Modules\Search\Transformers\OutletSearchResource;
use Modules\Search\Transformers\ProductSearchResource;
use Modules\Search\Transformers\ProductTypeSearchResource;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $search_for = $request->query('q');

        $product_types = ProductType::whereHas('translations', function ($query) use ($search_for) {
            $query->where('name', 'like', '%' . $search_for . '%')
                    ->orWhere('description', 'like', '%' . $search_for . '%')
                    ->where('name', '!=', 'Default product type');
        })->get()->take(50);

        if($request->user() && !request()->query('location'))
        {
            $outlets_ids = $request->user()->deliveringOutlets->outlets;

            $products = Product::whereIn('outlet_id', $outlets_ids)
            ->whereHas('menu')
            ->whereHas('productType.translations', function ($query) use ($search_for) {
                $query->where('name', '!=', 'Default product type');
            })
            ->whereHas('translations', function ($query) use ($search_for) {
                $query->where('name', 'like', '%' . $search_for . '%')
                    ->orWhere('description', 'like', '%' . $search_for . '%');
            })
            ->get()->take(100);

            $outlets = Outlet::whereIn('id', $outlets_ids)
            ->whereHas('translations', function ($query) use ($search_for) {
                $query->where('name', 'like', '%' . $search_for . '%')
                    ->orWhere('info', 'like', '%' . $search_for . '%');
            })->get()->take(50);

            return response([
                'message' => __('success_action', ['model' => __('search_results'), 'action' => __('retrieved')]),
                'product_types' => ProductTypeSearchResource::collection($product_types),
                'products' => ProductSearchResource::collection($products),
                'outlets' => OutletSearchResource::collection($outlets),
            ]);
        }


        $products = Product::whereHas('menu')
        ->whereHas('productType.translations', function ($query) use ($search_for) {
            $query->where('name', '!=', 'Default product type');
        })
        ->whereHas('translations', function ($query) use ($search_for) {
            $query->where('name', 'like', '%' . $search_for . '%')
                ->orWhere('description', 'like', '%' . $search_for . '%');
        })
        ->get()->take(100);
        $products = Helpers::getOnlyProductssDelivering($products);

        $outlets = Outlet::whereHas('translations', function ($query) use ($search_for) {
            $query->where('name', 'like', '%' . $search_for . '%')
                ->orWhere('info', 'like', '%' . $search_for . '%');
        })->get()->take(50);
        $outlets = Helpers::getOnlyOutletsDelivering($outlets);

        // $product_types = $product_types->map(function ($item, $key){
        //     return $item->only(['id', 'name', 'media']);
        // });

        return response([
            'message' => __('success_action', ['model' => __('search_results'), 'action' => __('retrieved')]),
            'product_types' => ProductTypeSearchResource::collection($product_types),
            'products' => ProductSearchResource::collection($products),
            'outlets' => OutletSearchResource::collection($outlets),
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexSeller(Request $request)
    {
        if($request->user() && $request->user()->type != 'seller')
        {
            return response(['message' => 'Unauthenticated'], 401);
        }

        $search_for = $request->query('q');
        $seller_id = $request->user()->id;

        $products = Product::whereHas('translations', function ($query) use ($search_for) {
            $query->where('name', 'like', '%' . $search_for . '%')
                ->orWhere('description', 'like', '%' . $search_for . '%');
        })
        ->whereHas('outlet', function ($query) use ($seller_id) {
            $query->where('user_id', $seller_id);
        })
        ->whereHas('menu')
        ->get();

        $outlets = Outlet::whereHas('translations', function ($query) use ($search_for) {
            $query->where('name', 'like', '%' . $search_for . '%')
                ->orWhere('info', 'like', '%' . $search_for . '%');
        })
        ->where('user_id', $seller_id)
        ->get();

        return response([
            'message' => __('success_action', ['model' => __('search_results'), 'action' => __('retrieved')]),
            'products' => ProductSearchResource::collection($products),
            'outlets' => OutletSearchResource::collection($outlets),
        ]);
    }

}
