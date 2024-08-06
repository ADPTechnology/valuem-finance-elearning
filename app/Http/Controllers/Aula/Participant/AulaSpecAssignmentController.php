<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Assignable;
use App\Models\Assignment;
use App\Models\Certification;
use App\Models\Event;
use App\Models\File;
use App\Models\GroupEvent;
use App\Models\ParticipantGroup;
use App\Models\SpecCourse;
use App\Services\AssignablesService;
use App\Services\FileService;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;


class AulaSpecAssignmentController extends Controller
{

    protected $assignableService;

    public function __construct(AssignablesService $service)
    {
        $this->assignableService = $service;
    }

    public function index(SpecCourse $specCourse)
    {

        $groupEvents = $this->assignableService->getGroupEvents($specCourse);

        return view('aula.viewParticipant.specCourses.assignments.index', compact('groupEvents', 'specCourse'));
    }

    public function show(Event $event)
    {

        $event->load([
            'groupEvent',
            'groupEvent.specCourse'
        ]);

        $assignments = $this->assignableService->showEventsAssignments($event);


        return view('aula.viewParticipant.specCourses.assignments.view', compact('assignments', 'event'));

    }



    // * ------------------------

    public function showAssignmentInfo(Assignment $assignment)
    {

        $user = Auth::user();

        try {

            $assignment->load([
                'files' => fn($q) => $q->where('file_type', 'archivos'),
                'certifications' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'certifications.user:id,name,paternal,maternal',
                'participantGroups' => function ($query) use ($user) {
                    $query->whereHas('participants', function ($q) use ($user) {
                        $q->where('participant_id', $user->id);
                    })->with('participants');
                },
                'assignables' => function ($query) use ($user) {
                    $query->where('assignable_type', Certification::class)
                        ->whereExists(function ($query) use ($user) {
                            $query->select(DB::raw(1))
                                ->from('certifications')
                                ->whereRaw('certifications.id = assignables.assignable_id')
                                ->where('user_id', $user->id);
                        })
                        ->orWhere('assignable_type', ParticipantGroup::class)
                        ->whereExists(function ($query) use ($user) {
                            $query->select(DB::raw(1))
                                ->from('group_participant')
                                ->whereRaw('group_participant.group_id = assignables.assignable_id')
                                ->where('participant_id', $user->id);
                        })
                        ->with('files');
                },
            ]);


            $html = view('aula.viewParticipant.specCourses.assignments.components._info_assignment', compact('assignment'))->render();
            $success = true;

        } catch (Exception $th) {
            $success = false;
        }

        return response()->json([
            'html' => $html,
            'assignment' => $assignment
        ]);

    }


    public function showAssignable(Assignment $assignment)
    {
        $user = Auth::user();

        $assignment->load([
            'assignables' => function ($query) use ($user) {
                $query->where('assignable_type', Certification::class)
                    ->whereExists(function ($query) use ($user) {
                        $query->select(DB::raw(1))
                            ->from('certifications')
                            ->whereRaw('certifications.id = assignables.assignable_id')
                            ->where('user_id', $user->id);
                    })
                    ->orWhere('assignable_type', ParticipantGroup::class)
                    ->whereExists(function ($query) use ($user) {
                        $query->select(DB::raw(1))
                            ->from('group_participant')
                            ->whereRaw('group_participant.group_id = assignables.assignable_id')
                            ->where('participant_id', $user->id);
                    })
                    ->with('files');
            },
        ]);

        $html = view('aula.viewParticipant.specCourses.assignments.components._info_assignable', compact('assignment'))->render();


        return response()->json([
            'title' => $assignment->title,
            'html' => $html,
        ]);

    }


    // * ------------------------



    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');
        return app(FileService::class)->download($file, $storage);
    }

    public function storeAssignment(Assignment $assignment, Request $request)
    {

        $user = Auth::user();
        $storage = env('FILESYSTEM_DRIVER');

        try {

            $this->assignableService->storageFileParticipant($assignment, $request, $storage);

            // $assignment = $this->assignableService->showAssignments($assignment);

            // $files = $assignment->files;

            $assignment->load([
                'assignables' => function ($query) use ($user) {
                    $query->where('assignable_type', Certification::class)
                        ->whereExists(function ($query) use ($user) {
                            $query->select(DB::raw(1))
                                ->from('certifications')
                                ->whereRaw('certifications.id = assignables.assignable_id')
                                ->where('user_id', $user->id);
                        })
                        ->orWhere('assignable_type', ParticipantGroup::class)
                        ->whereExists(function ($query) use ($user) {
                            $query->select(DB::raw(1))
                                ->from('group_participant')
                                ->whereRaw('group_participant.group_id = assignables.assignable_id')
                                ->where('participant_id', $user->id);
                        })
                        ->with('files');
                },
            ]);


            $html = view('aula.viewParticipant.specCourses.assignments.components._info_assignable', compact('assignment'))->render();

            $success = true;

        } catch (Exception $th) {
            $success = false;
        }

        $message = $success ? 'Archivo subido correctamente' : 'Error al subir el archivo';

        return response()->json([
            'success' => $success,
            'html' => $html,
            'message' => $message
        ]);

    }

    public function showEventList(GroupEvent $groupEvent)
    {

        $user = Auth::user();

        $events = $groupEvent->events()
            ->whereHas('assignments', function ($q) use ($user) {
                $q->whereHas('certifications', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                    ->orWhereHas('participantGroups.participants', function ($q) use ($user) {
                        $q->where('participant_id', $user->id);
                    });
            })
            ->with('user:id,name,paternal,maternal')->get();

        $html = view('aula.viewParticipant.specCourses.assignments.components._events_list', compact('events'))->render();

        return response()->json([
            'html' => $html,
        ]);

    }


    // ------------------------*


    public function deleteFileParticipant(File $file, Assignment $assignment)
    {
        $user = Auth::user();

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->assignableService->deleFileParticipant($file, $assignment, $storage);

            $assignment->load([
                'assignables' => function ($query) use ($user) {
                    $query->where('assignable_type', Certification::class)
                        ->whereExists(function ($query) use ($user) {
                            $query->select(DB::raw(1))
                                ->from('certifications')
                                ->whereRaw('certifications.id = assignables.assignable_id')
                                ->where('user_id', $user->id);
                        })
                        ->orWhere('assignable_type', ParticipantGroup::class)
                        ->whereExists(function ($query) use ($user) {
                            $query->select(DB::raw(1))
                                ->from('group_participant')
                                ->whereRaw('group_participant.group_id = assignables.assignable_id')
                                ->where('participant_id', $user->id);
                        })
                        ->with('files');
                },
            ]);

            $html = view('aula.viewParticipant.specCourses.assignments.components._info_assignable', compact('assignment'))->render();

            $success = true;

        } catch (Exception $th) {
            $success = false;
        }

        $message = $success ? 'Archivo eliminado correctamente' : 'Error al eliminar el archivo';

        return response()->json([
            'success' => $success,
            'html' => $html,
            'message' => $message
        ]);

    }

}
