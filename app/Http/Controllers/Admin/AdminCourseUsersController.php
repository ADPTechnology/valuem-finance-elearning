<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ProductCertification;
use App\Services\FreeCourseService;
use Exception;
use Illuminate\Http\Request;

class AdminCourseUsersController extends Controller
{

    public $freeCourseService;

    public function __construct(FreeCourseService $freeCourseService)
    {
        $this->freeCourseService = $freeCourseService;
    }

    public function getDataTable(Course $course, Request $request)
    {
        if ($request->ajax()) {
            return $this->freeCourseService->getDataTableUsersOnCourse($course, $request);
        }
    }

    public function store(Request $request, Course $course)
    {
        $dnis = $request['users-selected'];

        try {
            $info = $this->freeCourseService->storeParticipantFreeCourse($dnis, $course);
        } catch (Exception $e) {
            $info = array("success" => true);
        }

        $message = getMessageFromSuccess($info['success'], 'stored');

        return response()->json([
            "success" => $info['success'],
            "message" => $message,
        ]);
    }

    public function getUsersList(Request $request, Course $course)
    {
        return $this->freeCourseService->getUsersTableCourse($request, $course);
    }


    public function updateUnlock(Request $request, ProductCertification $productCertification)
    {
        try {
            $success = $this->freeCourseService->updateUnlockCert($request, $productCertification);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function resetCertification(ProductCertification $productCertification)
    {
        try {
            $success = $this->freeCourseService->resetCertification($productCertification);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }


    public function destroy(ProductCertification $productCertification)
    {
        try {
            $success = $this->freeCourseService->destroyUserForCourse($productCertification);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }
}
