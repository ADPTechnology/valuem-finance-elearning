<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\OwnerCompany;
use App\Models\Room;
use App\Models\User;
use App\Services\EventService;
use Exception;
use Illuminate\Http\Request;

class EventFreeCourseLiveController extends Controller
{

    protected $eventService;

    public function __construct(EventService $service)
    {
        $this->eventService = $service;
    }


    public function index(Request $request, Course $course)
    {
        if ($request->ajax()) {
            return $this->eventService->getEventsFreeCourseLiveDataTable($course);
        }
    }


    // public function store(Request $request, Course $course)
    // {


    //     try {
    //         $eventModel = $this->eventService->specCourseStore($request, $module, $groupEvent);
    //         $success = $eventModel ? true : false;
    //     } catch (Exception $e) {
    //         $success = false;
    //     }

    //     $message = getMessageFromSuccess($success, 'stored');

    //     if ($success) {

    //         $specCourse = $module->specCourse;
    //         $specCourse->loadRelationships();
    //         $moduleActive = getActiveSection($request['id']);

    //         $groupEvent = $groupEvent->loadSpecCourse();

    //         $htmlModules = view('admin.groupEvent.partials.components._modules_list', compact('specCourse', 'moduleActive', 'groupEvent'))->render();
    //     }

    //     return response()->json([
    //         "success" => $success,
    //         "message" => $message,
    //         "htmlModule" => $htmlModules,
    //     ]);


    // }


    public function create()
    {
        $information = $this->eventService->informationForCreateEvent();

        return response()->json($information);
    }


}
