<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\{Course, Event};
use App\Models\WebinarEvent;
use App\Services\FreeCourseService;
use App\Services\Home\HomeCertificationService;
use App\Services\Home\HomeCourseService;
use App\Services\HomeWebinarService;
use Auth;
use Exception;
use Illuminate\Http\Request;

class HomeCertificationController extends Controller
{
    private $certificationService;

    public function __construct(HomeCertificationService $service)
    {
        $this->certificationService = $service;
    }

    public function UserCertificationSelfRegister(HomeCourseService $courseService, Request $request, Event $event)
    {
        $event->loadRelationships();

        $success = false;
        $html = NULL;
        $htmlEvents = NULL;
        $message = NULL;

        try {
            $this->certificationService->userSelfRegisterCertification($request, $event);
            $success = true;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if ($success) {
            $events = $courseService->getAvailableEvents($event->course);
            $htmlEvents = view('home.courses.partials._events_list', compact('events'))->render();
            $html = view('home.common.partials.boxes._event_success_message', compact('event'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html,
            "htmlEvents" => $htmlEvents
        ]);
    }


    public function requestRegistrationCourse(Course $course, Request $request)
    {

        $success = false;
        $html = NULL;
        $htmlEvents = NULL;
        $message = NULL;

        $user = Auth::user();

        $category = $course->courseCategory()->first();

        try {
            $this->certificationService->requestRegistrationCourse($request, $course, $user);
            $success = true;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if ($success) {

            $freeCourses = app(FreeCourseService::class)->withFreeCourseRelationshipsQuery()
                ->where('active', 'S')
                ->where('category_id', $category->id)
                ->having('course_chapters_count', '>', 0)
                ->get();

            $htmlEvents = view('home.freecourses.partials.boxes._freecourses_list', compact('freeCourses'))->render();
            $html = view('home.common.partials.boxes._course_request_message', compact('course'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html,
            "htmlEvents" => $htmlEvents
        ]);
    }


    public function UserExternalCertificationSelfRegister(HomeWebinarService $webinarService, Request $request, WebinarEvent $event)
    {
        $event->loadRelationShipsForWebinar();

        $success = false;
        $html = NULL;
        $htmlEvents = NULL;
        $message = NULL;

        try {
            $this->certificationService->userExternalSelfRegisterCertification($request, $event);
            $success = true;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if ($success) {
            $events = $webinarService->getAvailableEvents($event->webinar);
            $htmlEvents = view('home.webinar.partials._events_list', compact('events'))->render();
            $html = view('home.common.partials.boxes._event_success_message', compact('event'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html,
            "htmlEvents" => $htmlEvents
        ]);
    }
}
