<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupEvent;
use App\Models\SpecCourse;
use App\Services\GroupEventService;
use Exception;
use Illuminate\Http\Request;

class SpecCourseGroupEventsController extends Controller
{

    protected $groupEventService;

    public function __construct(GroupEventService $groupEventService)
    {
        $this->groupEventService = $groupEventService;
    }

    public function getDataTable(SpecCourse $specCourse)
    {
        return $this->groupEventService->getGroupsSpecCourse($specCourse);
    }

    public function store(Request $request, SpecCourse $specCourse)
    {

        try {

            $groupEvent = $this->groupEventService->store($request, $specCourse);
            $success = $groupEvent ? true : false;

        } catch (Exception $th) {
            $success = false;
        }

        return response()->json([
            "success" => $success,
            "message" => getMessageFromSuccess($success, 'stored'),
        ]);

    }

    public function show(GroupEvent $groupEvent)
    {

        $groupEvent->loadSpecCourse();

        $moduleActive = '';

        return view('admin.groupEvent.show', compact(
                'groupEvent',
                'moduleActive'
            )
        );
    }

    public function edit(GroupEvent $groupEvent)
    {
        $groupEvent = $this->groupEventService->edit($groupEvent);

        return response()->json([
            "success" => true,
            "groupEvent" => $groupEvent,
        ]);
    }


    public function update(GroupEvent $groupEvent, Request $request)
    {

        try {

            $groupEvent = $this->groupEventService->update($groupEvent, $request);
            $success = $groupEvent ? true : false;

        } catch (Exception $th) {
            $success = false;
        }

        return response()->json([
            "success" => $success,
            'message' => getMessageFromSuccess($success, 'updated'),
        ]);
    }


    public function destroy(GroupEvent $groupEvent)
    {
        try {

            $success = $groupEvent->delete();

        } catch (Exception $th) {
            $success = false;
        }

        return response()->json([
            "success" => $success,
            'message' => getMessageFromSuccess($success, 'deleted'),
        ]);
    }



}
