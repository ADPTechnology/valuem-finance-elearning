<?php
namespace App\Services;

use App\Models\Assignable;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\ParticipantGroup;
use App\Models\SpecCourse;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Models\Certification;

class AssignablesService
{

    public function getGroupEvents(SpecCourse $specCourse)
    {
        $user = Auth::user();

        return $specCourse->groupEvents()
            ->whereHas('events', function ($query) use ($user) {
                $query->whereHas('assignments', function ($q) use ($user) {
                    $q->whereHas('certifications', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                        ->orWhereHas('participantGroups.participants', function ($q) use ($user) {
                            $q->where('participant_id', $user->id);
                        });
                });
            })
            ->get();

    }

    public function showEventsAssignments(Event $event)
    {
        $user = Auth::user();

        return $event->assignments()
            ->where(function ($query) use ($user) {
                $query->whereHas('certifications', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                    ->orWhereHas('participantGroups.participants', function ($query) use ($user) {
                        $query->where('participant_id', $user->id);
                    });
            })
            ->with([
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
                        });
                },
            ])
            ->get();

    }

    public function showAssignments(Assignment $assignment)
    {
        $user = Auth::user();

        $assignment->load([
            'file' => fn($q) => $q->where('file_type', 'archivos'),
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
                    });
            },
            'assignables.files'
        ]);

        $assignment['date_humans'] = getDateForHummans($assignment->date_limit);

        return $assignment;
    }


    public function storageFileParticipant(Assignment $assignment, Request $request, $storage)
    {

        $user = Auth::user();

        $assignable = $assignment->assignables()
            ->where(function ($query) use ($user) {
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
                    });
            })
            ->first();

        if ($request->content) {
            $assignable->update([
                'notes' => $request->content
            ]);
        }

        if ($assignable && $request->hasFile('files')) {

            $assignable->update([
                'status' => 'entregado',
            ]);

            foreach ($request->file('files') as $file) {

                $file_type = 'archivos';
                $category = 'asignaciones';
                $file;
                $belongsTo = 'asignaciones';
                $relation = 'one_many';

                app(FileService::class)->store(
                    $assignable,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );

            }

        }


    }



    // --------------------

    public function deleFileParticipant($file, $assignment, $storage)
    {
        app(FileService::class)->destroy($file, $storage);
    }

}
