<?php

namespace App\Http\Controllers\Aula\Instructor;

use App\Http\Controllers\Controller;
use App\Models\{CourseModule, Event, SpecCourse};
use App\Services\{CourseModuleService};
use Illuminate\Http\Request;

class AulaSpecModuleController extends Controller
{
    private $courseModuleService;

    public function __construct(CourseModuleService $service)
    {
        $this->courseModuleService = $service;
    }

    public function index(Request $request, SpecCourse $specCourse)
    {
        if ($request->ajax()) {
            return $this->courseModuleService->getDataTable($specCourse);
        }

        return view('aula.instructor.specModules.index', compact(
            'specCourse'
        ));
    }

    public function show(Request $request, CourseModule $module)
    {
        if ($request->ajax()) {
            return $this->courseModuleService->getEventsDataTable($module);
        }

        $module->load('specCourse');

        return view('aula.instructor.specModules.show', compact(
            'module'
        ));
    }

    public function showParticipants(Request $request, Event $event)
    {
        if ($request->ajax()) {
            return $this->courseModuleService->getParticipantsDataTable($event);
        }

        $event->load([
            'specCourse', 'courseModule', 'groupEvent',
            'assignments' => fn ($q) =>
                $q->where('active', 'S')
        ]);

        return view('aula.instructor.specModules.participants', compact(
            'event'
        ));
    }
}
