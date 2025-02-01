<?php

namespace Modules\Country\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Country\Entities\Language;

class LanguageController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return response([
            'message' => __('success_action', ['model' => __('language'), 'action' => __('retrieved')]),
            'language' => Language::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Language::class);

        $request->validate([
            'name' => 'required|min:2|max:100',
            'alpha3code' => 'sometimes|min:2|max:3',
        ]);

        $language = Language::create([
            'name' => $request->name,
            'alpha3code' => $request->alpha2code,
        ]);

        return response([
            'message' => __('success_action', ['model' => __('language'), 'action' => __('added')]),
            'language' => $language
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Language $language)
    {
        $this->authorize('create', $language);

        $request->validate([
            'name' => 'sometimes|min:2|max:100',
            'alpha3code' => 'sometimes|min:2|max:3',
        ]);

        $language->update($request->only(['name', 'alpha3code']));

        return response([
            'message' => __('success_action', ['model' => __('language'), 'action' => __('updated')]),
            'language' => $language->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Language $language)
    {
        $this->authorize('create', $language);

        $language->delete();

        return response([
            'message' => __('success_action', ['model' => __('language'), 'action' => __('deleted')])
        ]);
    }
}
