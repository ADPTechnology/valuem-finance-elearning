<?php

namespace App\Http\Controllers\Aula\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\{Certification, Course, Event, User};
use App\Services\{CertificationService, EventService};
use Illuminate\Http\Request;

class EventsController extends Controller
{
    private $eventService;

    public function __construct(EventService $service)
    {
        $this->eventService = $service;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->eventService->getSuperVisorDataTable($request);
        }

        $courses = Course::where('course_type', 'REGULAR')->get(['id', 'description', 'course_type']);
        $instructors = User::getInstructorsQuery()->get(['id', 'name', 'paternal']);
        $responsables = User::getResponsablesQuery()->get(['id', 'name', 'paternal']);

        return view('aula.supervisor.events.index', compact(
            'courses',
            'instructors',
            'responsables'
        ));
    }

    public function show(Request $request, Event $event)
    {
        if ($request->ajax()) {
            return app(CertificationService::class)->getSupervisorParticipantsDataTable($request, $event);
        }

        $event->loadRelationships();

        return view('aula.supervisor.events.show', compact(
            'event'
        ));
    }

    public function showCertification(Certification $certification)
    {
        $certification->loadRelationships();

        $html = view('aula.supervisor.events.partials.components._content_show_cert', compact('certification'))->render();

        return response()->json([
            "html" => $html
        ]);
    }
}
