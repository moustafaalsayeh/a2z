<?php

namespace Modules\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Menu\Entities\Menu;
use Illuminate\Routing\Controller;
use Modules\Menu\Filters\MenuFilter;
use Modules\Outlet\Entities\Outlet;
use Modules\Menu\Transformers\MenuResource;
use Modules\Product\Entities\Product;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(MenuFilter $request)
    {
        $menus = Menu::filter($request)->paginate(20);

        return MenuResource::collection($menus);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => [
                'bail',
                'required',
                'exists:outlets,id',
                function($attribute, $value, $fail){
                    if(auth('api')->user()->type == 'seller' && Outlet::findOrFail($value)->user->id != auth('api')->user()->id)
                    {
                        $fail(__('outlet_doesnt_belong'));
                    }
                }
            ],
            'name' => 'required|min:2|max:100',
            'description' => 'sometimes|min:2|max:1000',
            'products' => 'sometimes|array',
            'products.*' => [
                'exists:products,id'
            ]
        ]);

        $menu = Menu::create($request->only(['outlet_id']));

        $menu->products()->attach($request->products);

        return response([
            'message' => __('success_action', ['model' => __('menu'), 'action' => __('added')]),
            'menu' => new MenuResource($menu)
        ]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'sometimes|min:2|max:100',
            'description' => 'sometimes|min:2|max:1000',
            'products' => 'sometimes|array',
            'products.*' => 'exists:products,id'
        ]);

        $menu->update($request->only([]));

        $menu->products()->syncWithoutDetaching($request->products);

        return response([
            'message' => __('success_action', ['model' => __('menu'), 'action' => __('updated')]),
            'product' => new MenuResource($menu)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response([
            'message' => __('success_action', ['model' => __('menu'), 'action' => __('deleted')])
        ]);
    }

    public function detachProduct(Menu $menu, $id)
    {
        $menu->products()->detach($id);

        return response([
            'message' => __('success_action', ['model' => __('product'), 'action' => __('removed')]),
            'product' => new MenuResource($menu->fresh())
        ]);
    }
}
