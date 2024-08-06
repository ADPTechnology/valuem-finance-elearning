<?php
namespace App\Services;

use App\Models\Assignable;
use App\Models\Assignment;
use App\Models\Certification;
use App\Models\Event;
use App\Models\File;
use App\Models\ParticipantGroup;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;


class AssignmentService
{
    public function getDatatable(Event $event)
    {

        $query = $event->assignments()->withCount([
            'assignables' => function ($query) {
                $query->where('status', 'entregado');
            },
        ]);

        $allAssignments = DataTables::of($query)
            ->editColumn('title', function ($assignment) {
                return $assignment->title;
                // ' . route('admin.assignments.show', $assignment) . '
            })
            ->editColumn('description', function ($assignment) {
                return $assignment->description ?? '-';
            })
            ->editColumn('flg_groupal', function ($assignment) {

                $isGroupal = $assignment->flg_groupal == 1 ? 'Es grupal' : 'Es individual';


                $flg_groupal = '<span class="badge badge-fill badge-primary">' . $isGroupal . '</span>';


                return $flg_groupal;

            })
            ->editColumn('flg_evaluable', function ($assignment) {

                $isEvaluable = $assignment->flg_evaluable == 1 ? 'Es evaluable' : 'No es evaluable';

                $flg_evaluable = '<span class="badge badge-fill badge-secondary">' . $isEvaluable . '</span>';


                return $flg_evaluable;


            })
            ->editColumn('active', function ($assignment) {
                return getStatusButton($assignment->active);
            })
            ->addColumn('action', function ($assignment) {

                $btn = '';

                $btn .= '<button data-toggle="modal" data-id="' .
                    $assignment->id . '"
                                            data-send="' . route('admin.specCourses.events.assignments.getDocuments', $assignment) . '"
                                            data-url="' . route('admin.specCourses.events.assignments.uploadDocuments', $assignment) . '"
                                            data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                                            showDocsAssignment-btn"><i class="fa-solid fa-file-lines"></i></button>';

                $btn .= '<button data-toggle="modal" data-id="' .
                    $assignment->id . '" data-url="' . route('admin.specCourses.events.assignments.update', $assignment) . '"
                                        data-send="' . route('admin.specCourses.events.assignments.edit', $assignment) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editAssignment-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $assignment->assignables_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $assignment->id . '" data-original-title="delete"
                                            data-url="' . route('admin.specCourses.events.assignments.destroy', $assignment) . '" class="me-3 edit btn btn-danger btn-sm
                                            deleteAssignment-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'flg_groupal', 'flg_evaluable', 'active', 'action'])
            ->make(true);

        return $allAssignments;

    }

    public function store(Request $request, Event $event, $storage)
    {

        $data = $request->all();

        $data['flg_groupal'] = isset($data['flg_groupal']) == 'on' ? 1 : 0;
        $data['flg_evaluable'] = isset($data['flg_evaluable']) == 'on' ? 1 : 0;
        $data['active'] = isset($data['active']) == 'on' ? 'S' : 'N';


        //? 1. crear el assignment 📌

        $assignment = $event->assignments()->create($data);

        //? * Crear archivo si se envia al request un file

        if ($assignment && $request->hasFile('files')) {

            foreach ($request->file('files') as $file) {

                $file_type = 'archivos';
                $category = 'asignables';
                $file;
                $belongsTo = 'asignables';
                $relation = 'one_many';

                app(FileService::class)->store(
                    $assignment,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );

            }

        }

        //? 2. condicionar si es grupal o no 📌

        $storedAssignableData = [
            'notes' => null,
            'points' => null,
            'status' => 'pendiente',
        ];

        if ($data['flg_groupal'] == 1) {

            $groupParticipants = $data['group_participants'];
            $allGroups = ParticipantGroup::whereIn('id', $groupParticipants)->get();

            foreach ($allGroups as $group) {
                $group->assignments()->attach($assignment, $storedAssignableData);
            }

        } else {

            //? 3. condicionar si es indivual o no 📌

            $allCertifications = $event->certifications()->where('evaluation_type', 'certification')->get();

            foreach ($allCertifications as $certification) {
                $certification->assignments()->attach($assignment, $storedAssignableData);
            }

        }

        return true;

    }


    public function update(Request $request, Assignment $assignment)
    {

        $data = $request->all();

        $data['flg_evaluable'] = isset($data['flg_evaluable']) == 'on' ? 1 : 0;
        $data['active'] = isset($data['active']) == 'on' ? 'S' : 'N';

        $newData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'value' => $data['value'],
            'flg_evaluable' => $data['flg_evaluable'],
            'date_limit' => $data['date_limit'],
            'active' => $data['active'],
        ];

        $assignment->update($newData);

        return true;

    }

    public function destroy(Assignment $assignment, $storage)
    {
        if ($assignment->file) {
            app(FileService::class)->destroy($assignment->file, $storage);
        }
        $assignment->assignables()->delete();
        $assignment->delete();
    }

    public function uploadDocuments(Assignment $assignment, Request $request, $storage)
    {
        if ($assignment && $request->hasFile('files')) {

            foreach ($request->file('files') as $file) {

                $file_type = 'archivos';
                $category = 'asignables';
                $file;
                $belongsTo = 'asignables';
                $relation = 'one_many';

                app(FileService::class)->store(
                    $assignment,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );

            }

        }

        return true;
    }

    public function destroyFile(File $file, $storage)
    {
        if ($file) {
            return app(FileService::class)->destroy($file, $storage);
        }
    }


    //--------------------- INSTRUCTOR ------------------------

    public function getAssignablesList(Assignment $assignment, Event $event)
    {
        $assignables = [];

        if ($assignment->flg_groupal == 1) {

            $eventParticipantsIds = getEventParticipantsIds($event);

            $assignables = $assignment->participantGroups()->with([
                'participants' => function ($q) use ($event, $eventParticipantsIds) {
                    $q->whereHas('certifications', function ($q) use ($event, $eventParticipantsIds) {
                        $q->where('event_id', $event->id)
                            ->where('evaluation_type', 'certification')
                            ->whereIn('user_id', $eventParticipantsIds);
                    })
                        ->with('certifications', function ($q) use ($event, $eventParticipantsIds) {
                            $q->where('event_id', $event->id)
                                ->where('evaluation_type', 'certification')
                                ->whereIn('user_id', $eventParticipantsIds);
                        });
                }
            ])
                ->withCount('participants')
                ->get();
        } else {
            $assignables = $assignment->certifications()->with('user')->get();
        }

        return $assignables;
    }

    public function getAssignableByType(Assignment $assignment, $type, $id)
    {
        if ($type == 'group') {
            $model = ParticipantGroup::find($id);
            $model->load([
                'assignments' => function ($q) use ($assignment) {
                    $q->where('assignments.id', $assignment->id);
                }
            ]);

            $assignable = $assignment->assignables()->where('assignable_type', ParticipantGroup::class)
                ->where('assignable_id', $model->id)
                ->with('files')
                ->first();
        } else {
            $model = Certification::find($id);
            $model->load('user');

            $assignable = $assignment->assignables()->where('assignable_type', Certification::class)
                ->where('assignable_id', $model->id)
                ->with('files')
                ->first();
        }

        $flg_group = $type == 'group' ? true : false;

        return [$assignable, $model, $flg_group];
    }

    public function updateAssignmentScore($request, Assignable $assignable)
    {
        if (
            $assignable->update(
                [
                    "points" => $request['points'],
                    "status" => 'revisado'
                ]
            )
        ) {
            return true;
        }

        return false;
    }


}
