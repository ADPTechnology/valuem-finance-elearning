<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseModule\StoreCourseModuleRequest;
use App\Models\SpecCourse;
use App\Services\CourseModuleService;
use Exception;
use Illuminate\Http\Request;

class GroupEventModuleController extends Controller
{
    private $courseModuleService;

    public function __construct(CourseModuleService $service)
    {
        $this->courseModuleService = $service;
    }

    public function store(StoreCourseModuleRequest $request, SpecCourse $specCourse)
    {
        $htmlSecCourse = null;
        $htmlModules = null;

        try {
            $courseModule = $this->courseModuleService->store($request, $specCourse);
            $success = $courseModule ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        if ($success) {

            $specCourse->loadRelationships();
            $moduleActive = getActiveSection($request['id']);

            // $htmlSecCourse = view('admin.groupEvent.partials.components._groupEvent', compact('specCourse'))->render();
            $htmlModules = view('admin.groupEvent.partials.components._modules_list',compact('groupEvent', 'moduleActive'))->render();

        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "htmlSecCourse" => $htmlSecCourse,
            "htmlModules" => $htmlModules
        ]);
    }




}
