<?php

namespace App\Http\Controllers\Aula\Instructor;
use App\Models\{
    Course,
    Event
};
use Auth;
use App\Http\Controllers\Controller;
use App\Services\{CertificationService, EventService};
use Illuminate\Http\Request;

class AulaEventsInsController extends Controller
{
    private $eventService;
    private $certificationService;

    public function __construct(EventService $eventService, CertificationService $certificationService)
    {
        $this->eventService = $eventService;
        $this->certificationService = $certificationService;
    }

    public function index(Request $request, Course $course)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            return $this->eventService->getInstructorEventsDatatable($user, $course);
        }

        return view('aula.instructor.events.index', compact(
            'course'
        ));
    }

    public function show(Request $request, Event $event) 
    {
        $user = Auth::user();

        if ($request->ajax()) {
            return $this->certificationService->getInsParticipantsDatatable($user, $event);
        }

        $event->load('course');

        return view('aula.instructor.events.show', compact(
            'event'
        ));
    }
}
