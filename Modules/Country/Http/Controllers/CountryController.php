<?php

namespace Modules\Country\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Country\Entities\Country;
use Modules\Country\Transformers\CountryResource;

class CountryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $countries = new Country();
        if ($request->query('alpha2code')) {
            $codes = explode(',', $request->query('alpha2code'));
            $countries = $countries->whereIn('alpha2code', $codes);
        }

        return response([
            'message' => __('success_action', ['model' => __('country'), 'action' => __('retrieved')]),
            'country' => CountryResource::collection($countries->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Country::class);

        $request->validate([
            'name' => 'required|min:2|max:100',
            'alpha3code' => 'sometimes|min:2|max:3',
        ]);

        $country = Country::create([
            'alpha3code' => $request->alpha2code,
        ]);

        return response([
            'message' => __('success_action', ['model' => __('country'), 'action' => __('added')]),
            'country' => new CountryResource($country)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Country $country)
    {
        $this->authorize('update', $country);

        $request->validate([
            'name' => 'sometimes|min:2|max:100',
            'alpha3code' => 'sometimes|min:2|max:3',
        ]);

        $country->update($request->only(['alpha3code']));

        return response([
            'message' => __('success_action', ['model' => __('country'), 'action' => __('updated')]),
            'country' => new CountryResource($country->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Country $country)
    {
        $this->authorize('delete', $country);

        $country->delete();

        return response([
            'message' => __('success_action', ['model' => __('country'), 'action' => __('deleted')])
        ]);
    }
}
