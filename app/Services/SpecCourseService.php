<?php

namespace App\Services;

use App\Models\{Certification, ParticipantGroup, SpecCourse};
use Auth;
use Datatables;
use DB;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;


class SpecCourseService
{
    public function getDataTable()
    {
        $query = SpecCourse::withCount('modules', 'groupEvents');

        $allSpecCourses = DataTables::of($query)
            ->editColumn('title', function ($specCourse) {
                return '<a href="' . route('admin.specCourses.show', $specCourse) . '">' . $specCourse->title . '</a>';
            })
            ->editColumn('subtitle', function ($specCourse) {
                return $specCourse->subtitle ?? '-';
            })
            ->editColumn('time_start', function ($specCourse) {
                return getTimeforHummans($specCourse->time_start);
            })
            ->editColumn('time_end', function ($specCourse) {
                return getTimeforHummans($specCourse->time_end);
            })
            ->editColumn('active', function ($specCourse) {
                return getStatusButton($specCourse->active);
            })
            ->addColumn('action', function ($specCourse) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $specCourse->id . '"
                    data-url="' . route('admin.specCourses.update', $specCourse) . '"
                    data-send="' . route('admin.specCourses.edit', $specCourse) . '"
                    data-place="index"
                    data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                    editSpecCourse"><i class="fa-solid fa-pen-to-square"></i></button>';

                if ($specCourse->modules_count == 0 && $specCourse->group_events_count == 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $specCourse->id . '" data-original-title="delete"
                        data-url="' . route('admin.specCourses.destroy', $specCourse) . '"
                        data-place="index"
                        class="ms-3 edit btn btn-danger btn-sm
                        deleteSpecCourse"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'active', 'action'])
            ->make(true);

        return $allSpecCourses;
    }

    public function store($request, $storage)
    {
        $data = normalizeInputStatus($request->validated());
        $data['time_start'] = Carbon::createFromFormat('g:i A', $data['time_start'])->format('H:i:s');
        $data['time_end'] = Carbon::createFromFormat('g:i A', $data['time_end'])->format('H:i:s');

        if ($specCourse = SpecCourse::create($data)) {

            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'cursosespec';
                $belongsTo = 'cursosespec';
                $relation = 'one_one';

                $file = $request->file('image');

                // Aqui cuando se creaba un curso de espc y se añadia una imagen redirigia cn el id del file. Ejem: ver/3887
                // por eso quité el return

                app(FileService::class)->store(
                    $specCourse,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $specCourse;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, SpecCourse $specCourse, $storage)
    {
        $data = normalizeInputStatus($request->validated());
        $data['time_start'] = Carbon::createFromFormat('g:i A', $data['time_start'])->format('H:i:s');
        $data['time_end'] = Carbon::createFromFormat('g:i A', $data['time_end'])->format('H:i:s');

        if ($specCourse->update($data)) {

            if ($request->hasFile('image')) {

                app(FileService::class)->destroy($specCourse->file, $storage);

                $file_type = 'imagenes';
                $category = 'cursosespec';
                $belongsTo = 'cursosespec';
                $relation = 'one_one';

                $file = $request->file('image');

                return app(FileService::class)->store(
                    $specCourse,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(SpecCourse $specCourse, $storage)
    {
        if ($specCourse->file) {
            app(FileService::class)->destroy($specCourse->file, $storage);
        }

        return $specCourse->delete();
    }


    // ------------- AULA ------------------

    public function getSpecCourses()
    {
        $user = Auth::user();

        $query = SpecCourse::with([

            'groupEvents' => fn($q) => $q->with([

                'events' => fn($q2) =>
                    $q2->with(
                        [
                            'user:id,name,paternal',
                        ]
                    )
                        ->select('events.id', 'events.course_module_id', 'events.group_event_id', 'events.user_id')
                        ->where('events.active', 'S'),

            ])->withMax('events', 'date'),

            'file' => fn($q) => $q->where('file_type', 'imagenes'),

        ])
            ->where('active', 'S');

        // CHANGE

        if ($user->role == 'instructor') {

            $query = $query->whereHas('groupEvents.events', function ($q) use ($user) {
                $q->where('events.user_id', $user->id);
            })
                ->withCount([
                    'specCourseCertifications as participants_count' => function ($q) use ($user) {
                        $q->select(DB::raw('count(distinct(certifications.user_id))'))
                            ->where('events.user_id', $user->id)
                            ->where('events.active', 'S');
                    }
                ]);
        } elseif ($user->role == 'participants') {

            $query = $query->whereHas('specCourseCertifications', function ($q) use ($user) {
                                $q->where('certifications.user_id', $user->id);
                            })
                    ->withCount([
                        'specCourseCertifications as participants_count' => fn($q) =>
                            $q->select(DB::raw('count(distinct(certifications.user_id))'))
                                ->where('events.active', 'S'),
                        'events as total_events_count' => fn($q) =>
                            $q->whereHas('certifications', function ($q) use ($user) {
                                $q->where('certifications.user_id', $user->id);
                            }),
                        'events as completed_events_count' => fn($q) =>
                            $q->whereHas('certifications', function ($q) use ($user) {
                                $q->where('certifications.user_id', $user->id)
                                    ->where('certifications.evaluation_type', 'certification')
                                    ->where('certifications.status', 'finished');
                            }),
                        'events as events_with_assignments_count' => fn($q) =>
                            $q->has('assignments')
                    ])
                    ->with(
                        [
                            'events.certifications' => fn($q) =>
                                $q->where('certifications.user_id', $user->id)
                                    ->where('certifications.status', 'finished')
                                    ->where('certifications.evaluation_type', 'certification')
                                    ->with('event:id,date')
                                    ->select('id', 'user_id', 'event_id', 'status', 'evaluation_type', 'score', 'score_fin'),
                        ]
                    )
                    ->with([
                        'events.assignments' => fn($q) =>
                            $q->where(function ($q) use ($user) {
                                $q->whereHas('certifications', function ($q) use ($user) {
                                    $q->where('certifications.user_id', $user->id);
                                });
                            })
                                ->orWhere(function ($q) use ($user) {
                                    $q->whereHas('participantGroups', function ($q) use ($user) {
                                        $q->whereHas('participants', function ($q) use ($user) {
                                            $q->where('participant_id', $user->id);
                                        });
                                    });
                                })
                                ->with([
                                    'certifications' => fn($q) =>
                                        $q->where('certifications.user_id', $user->id),
                                    'participantGroups' => fn($q) =>
                                        $q->whereHas('participants', function ($q) use ($user) {
                                            $q->where('participant_id', $user->id);
                                        }),
                                ])
                    ]);
        }

        $specCourses = $query->get()
                        ->sortByDesc('groupEvents.events.date');

        return $specCourses ?? collect();
    }

    // STORE FILE


    public function storeFiles(Request $request,SpecCourse $specCourse, $storage)
    {

        $file_type = 'archivos';
        $category = 'cursosespec';
        $belongsTo = 'cursosespec';
        $relation = 'one_many';

        foreach ($request->file('files') as $file) {

            $stored = app(FileService::class)->store(
                $specCourse,
                $file_type,
                $category,
                $file,
                $storage,
                $belongsTo,
                $relation
            );

        }

        if ($stored) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }


}
