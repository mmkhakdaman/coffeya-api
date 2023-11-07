<?php

namespace Modules\Tenant\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Tenant\Entities\WorkSchedule;
use Modules\Tenant\Transformers\WorkScheduleResource;

class AdminWorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return WorkScheduleResource::collection(
            WorkSchedule::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return WorkScheduleResource
     */
    public function store(Request $request): WorkScheduleResource
    {
        $request->validate(
            [
                'work_day' => 'required|integer|between:1,7',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
            ]
        );

        $workSchedule = WorkSchedule::create($request->all());

        return new WorkScheduleResource($workSchedule);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param WorkSchedule $workSchedule
     * @return WorkScheduleResource
     */
    public function update(Request $request, WorkSchedule $workSchedule): WorkScheduleResource
    {
        $request->validate(
            [
                'work_day' => 'required|integer|between:1,7',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
            ]
        );

        $workSchedule->update($request->all());

        return new WorkScheduleResource($workSchedule);
    }

    /**
     * Remove the specified resource from storage.
     * @param WorkSchedule $workSchedule
     * @return JsonResponse
     */
    public function destroy(WorkSchedule $workSchedule): JsonResponse
    {
        $workSchedule->delete();

        return Response()->json(
            [
                'message' => 'Work schedule deleted successfully',
            ]
        );
    }
}
