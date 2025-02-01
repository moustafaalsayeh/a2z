<?php

namespace Modules\Outlet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Outlet\Entities\WorkHour;

class WorkHourController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => [
                'bail',
                'exists:outlets,id',
                function ($attribute, $value, $fail) {
                    if (auth('api')->user()->type != 'admin' && !auth('api')->user()->outlets->contains($value)) {
                        $fail('this outlet doesn\'t belong to the logged in user');
                    }
                },
            ],
            'day' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'time_from'  => 'required|date_format:H:i',
            'time_to'  => 'required|date_format:H:i',
        ]);

        $work_hour = WorkHour::create($request->only('outlet_id', 'day', 'time_from', 'time_to'));

        return response([
            'message' => __('success_action', ['model' => __('work_hour'), 'action' => __('created')]),
            'work_hour' => $work_hour
        ]);
    }

    public function update(Request $request, WorkHour $work_hour)
    {
        $request->validate([
            'day' => 'sometimes|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'time_from'  => 'sometimes|date_format:H:i',
            'time_to'  => 'sometimes|date_format:H:i',
        ]);

        $work_hour->update($request->except('_method'));

        return response([
            'message' => __('success_action', ['model' => __('work_hour'), 'action' => __('updated')]),
            'work_hour' => $work_hour->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(WorkHour $work_hour)
    {
        $work_hour->delete();

        return response([
            'message' => __('success_action', ['model' => __('work_hour'), 'action' => __('deleted')])
        ]);
    }
}
