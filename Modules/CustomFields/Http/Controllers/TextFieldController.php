<?php

namespace Modules\CustomFields\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\CustomFields\Entities\TextField;
use Modules\CustomFields\Http\Requests\StoreTextFieldRequest;
use Modules\CustomFields\Http\Requests\UpdateTextFieldRequest;

class TextFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $text_fields = TextField::all();

        return response([
            'message' => __('success_action', ['model' => __('text_field'), 'action' => __('retrieved')]),
            'text_fields' => $text_fields
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreTextFieldRequest $request)
    {
        $text_field = TextField::create($request->validated());

        return response([
            'message' => __('success_action', ['model' => __('text_field'), 'action' => __('stored')]),
            'text_field' => $text_field
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateTextFieldRequest $request, TextField $text_field)
    {
        $text_field->update([
            'name' => $request->name,
            'type' => $request->type,
            'default' => $request->default,
            'placeholder' => $request->placeholder
        ]);

        return response([
            'message' => __('success_action', ['model' => __('text_field'), 'action' => __('updated')]),
            'text_field' => $text_field
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(TextField $text_field)
    {
        $text_field->delete();

        return response([
            'message' => __('success_action', ['model' => __('text_field'), 'action' => __('deleted')]),
        ]);
    }
}
