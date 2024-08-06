<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\{Company, CourseModule, Event, Exam, OwnerCompany, Room, User};
use App\Models\GroupEvent;
use App\Services\{CertificationService, EventService};
use Exception;
use Illuminate\Http\Request;

class SpecCourseEventsController extends Controller
{
    private $eventService;

    public function __construct(EventService $service)
    {
        $this->eventService = $service;
    }

    public function getDataTable(Request $request, CourseModule $module, GroupEvent $groupEvent)
    {
        if ($request->ajax()) {

            if ($request['type'] == 'html') {

                $html = view('admin.specCourses.partials.components._events_list', compact('module', 'groupEvent'))->render();

                return response()->json([
                    'html' => $html,
                    'title' => $module->title,
                ]);

            } else if ($request['type'] == 'table') {

                return $this->eventService->getSpecCourseDataTable($module->id, $groupEvent->id);

            }
        }

        abort(403);
    }

    public function create()
    {

        $information = $this->eventService->informationForCreateEvent();

        return response()->json($information);
    }

    public function store(EventRequest $request, CourseModule $module, GroupEvent $groupEvent)
    {

        try {
            $eventModel = $this->eventService->specCourseStore($request, $module, $groupEvent);
            $success = $eventModel ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        if ($success) {

            $specCourse = $module->specCourse;
            $specCourse->loadRelationships();
            $moduleActive = getActiveSection($request['id']);

            $groupEvent = $groupEvent->loadSpecCourse();

            // $htmlSecCourse = view('admin.specCourses.partials.components._specCourse_box', compact('specCourse'))->render();
            $htmlModules = view('admin.groupEvent.partials.components._modules_list', compact('specCourse', 'moduleActive', 'groupEvent'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            // "htmlCourse" => $htmlSecCourse,
            "htmlModule" => $htmlModules,
        ]);
    }

    public function edit(Event $event)
    {
        $event->loadRelationships()->loadParticipantsCount();

        $allExams = Exam::withCount(['questions' => fn($q) => $q->where('active', 'S')])->having('questions_count', '>=', 2)
            ->get(['id', 'title', 'exam_type']);

        $exams = $allExams->where('exam_type', 'dynamic');

        $event['type'] = verifyEventType($event->type);

        return response()->json([
            "all" => [
                "types" => $this->eventService->getTypes(),
                "instructors" => User::getInstructorsQuery()->get(['id', 'name', 'paternal']),
                "responsables" => User::getResponsablesQuery()->get(['id', 'name', 'paternal']),
                "rooms" => Room::where('capacity', '>=', $event->participants_count)->get(['id', 'description', 'capacity']),
                "ownerCompanies" => OwnerCompany::get(['id', 'name']),
                "exams" => $exams,
            ],
            "event" => $event
        ]);
    }

    public function update(EventRequest $request, Event $event)
    {

        $event->loadParticipantsCount();

        try {
            $success = $this->eventService->update($request, $event);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($request['place'] == 'show') {
            $event->loadRelationships();
            $html = view('admin.events.partials._box_event', compact('event'))->render();
            $show = true;
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "show" => $show ?? null,
            "html" => $html ?? null,
            "title" => mb_strtolower($event->description, 'UTF-8')
        ]);
    }

    public function destroy(Request $request, Event $event)
    {
        try {
            $success = $this->eventService->destroy($event);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        if ($success) {

            $specCourse = $event->courseModule->specCourse;

            $specCourse->loadRelationships();

            $groupEvent = $event->groupEvent->loadSpecCourse();

            $moduleActive = getActiveSection($request['id']);

            // $htmlSecCourse = view('admin.specCourses.partials.components._specCourse_box', compact('specCourse'))->render();
            $htmlModules = view('admin.groupEvent.partials.components._modules_list', compact('specCourse', 'moduleActive', 'groupEvent'))->render();

            if ($request['place'] == 'show') {
                $route = route('admin.specCourses.show', $specCourse);
                $show = true;
            }
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            // "htmlCourse" => $htmlSecCourse ?? null,
            "htmlModule" => $htmlModules ?? null,
            "route" => $route ?? null,
            "show" => $show ?? false,
        ]);
    }


    public function show(Request $request, Event $event)
    {
        if ($request->ajax()) {
            return app(CertificationService::class)->getParticipantsTable($request, $event);
        }

        $event->loadRelationships();
        $companies = Company::get(['id', 'description']);

        return view(
            'admin.specCourses.events.index',
            compact(
                'event',
                'companies'
            )
        );
    }
}
