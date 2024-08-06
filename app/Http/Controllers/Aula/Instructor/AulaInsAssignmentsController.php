<?php

namespace App\Http\Controllers\Aula\Instructor;

use App\Http\Controllers\Controller;
use App\Models\{Assignable, Assignment, Certification, ParticipantGroup};
use App\Services\{AssignmentService};
use Exception;
use Illuminate\Http\Request;

class AulaInsAssignmentsController extends Controller
{
    protected $assigmentService;

    public function __construct(AssignmentService $service)
    {
        $this->assigmentService = $service;
    }

    public function getAssignablesList(Assignment $assignment)
    {
        $assignment->load(['event', 'files']);
        $event = $assignment->event;

        $assignables = $this->assigmentService->getAssignablesList($assignment, $event);

        $html = view('aula.instructor.specCourses.groupEvents.partials.components._participants_assign_list', compact(
            'assignment',
            'assignables'
        ))->render();

        return response()->json([
            'html' => $html,
        ]);
    }

    public function getDataAssignable(Assignment $assignment, $type, $id)
    {
        [$assignable, $model, $flg_group] = $this->assigmentService->getAssignableByType($assignment, $type, $id);

        $html = view('aula.instructor.specCourses.groupEvents.partials.components._form_view_assign', compact(
            'assignment',
            'model',
            'assignable',
            'flg_group'
        ))->render();

        return response()->json([
            "assignable" => $assignable,
            "html" => $html,
            "model" => $model
        ]);
    }

    public function updateAssignmentScore(Request $request, Assignment $assignment, $type, $id)
    {
        $success = false;
        $html = '';

        if ($assignment->flg_evaluable == 1) {

            [$assignable, $model, $flg_group] = $this->assigmentService->getAssignableByType($assignment, $type, $id);

            $assignment->load(['event', 'files']);
            $event = $assignment->event;

            try {
                $success = $this->assigmentService->updateAssignmentScore($request, $assignable);
            } catch (Exception $e) {
                $success = false;
            }

            $assignables = $this->assigmentService->getAssignablesList($assignment, $event);

            $html = view('aula.instructor.specCourses.groupEvents.partials.components._participants_assign_list', compact(
                'assignment',
                'assignables'
            ))->render();
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "message"=> $message,
            "html" => $html,
        ]);
    }
}
