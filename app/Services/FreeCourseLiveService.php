<?php

namespace App\Services;

use App\Models\{Course};
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Request;
use Yajra\DataTables\Facades\DataTables;
use Auth;


class FreeCourseLiveService
{
    public function getDataTable()
    {
        $query = Course::withCount([
            'exams',
            'files' => fn($q) =>
                $q->where('file_type', 'archivos')
        ])
            ->where('course_type', 'LIVEFREECOURSE');

        $allCourses = DataTables::of($query)
            ->editColumn('course_type', function ($course) {
                return config('parameters.course_types')[$course->course_type] ?? '-';
            })
            ->editColumn('subtitle', function ($course) {
                return $course->subtitle ?? '-';
            })
            ->editColumn('description', function ($course) {
                return '<a href="' . route('admin.freeCourseLive.show', $course) . '"
                class="content-course-btn">' . $course->description . '</a>';
            })
            ->editColumn('time_start', function ($course) {
                return (Carbon::parse($course->time_start))->format('g:i A');
            })
            ->editColumn('time_end', function ($course) {
                return (Carbon::parse($course->time_end))->format('g:i A');
            })
            ->editColumn('active', function ($course) {
                return getStatusButton($course->active);
            })
            ->addColumn('action', function ($course) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $course->id . '" data-url="' . route('admin.freeCourseLive.update', $course) . '"
                                        data-send="' . route('admin.freeCourseLive.edit', $course) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editLiveFreeCourse"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $course->exams_count == 0 &&
                    $course->files_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $course->id . '" data-original-title="delete"
                                            data-place="index"
                                            data-url="' . route('admin.freeCourseLive.destroy', $course) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteliveFreeCourse"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['description', 'active', 'action'])
            ->make(true);

        return $allCourses;
    }

    public function store($request, $storage)
    {
        $data = normalizeInputStatus($request->validated());
        $data['time_start'] = Carbon::createFromFormat('g:i A', $data['time_start'])->format('H:i:s');
        $data['time_end'] = Carbon::createFromFormat('g:i A', $data['time_end'])->format('H:i:s');

        if (
            $course = Course::create($data + [
                "course_type" => 'LIVEFREECOURSE'
            ])
        ) {

            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'cursos';
                $belongsTo = 'cursos';
                $relation = 'one_one';

                $file = $request->file('image');

                app(FileService::class)->store(
                    $course,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $course;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, Course $course, $storage)
    {
        $data = normalizeInputStatus($request->validated());
        $data['time_start'] = Carbon::createFromFormat('g:i A', $data['time_start'])->format('H:i:s');
        $data['time_end'] = Carbon::createFromFormat('g:i A', $data['time_end'])->format('H:i:s');

        if ($course->update($data)) {

            app(FileService::class)->destroy($course->file, $storage);

            if ($request->hasFile('image')) {
                $file_type = 'imagenes';
                $category = 'cursos';
                $belongsTo = 'cursos';
                $relation = 'one_one';

                $file = $request->file('image');

                app(FileService::class)->store(
                    $course,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $course;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(Course $course, $storage)
    {
        if ($course->file) {
            app(FileService::class)->destroy($course->file, $storage);
        }

        return $course->delete();
    }


    // ------------ FILES -----------------

    public function getFilesDataTable(Course $course)
    {
        $query = $course->files()->where('file_type', 'archivos');

        return DataTables::of($query)
            ->editColumn('file_path', function ($file) {
                return '<a href="' . route('admin.freeCourseLive.files.download', $file) . '">' .
                    $file->name
                    . '</a> ';
            })
            ->editColumn('file_type', function ($file) {
                return ucwords($file->file_type);
            })
            ->editColumn('category', function ($file) {
                return ucwords($file->category);
            })
            ->editColumn('created_at', function ($file) {
                return $file->created_at;
            })
            ->editColumn('updated_at', function ($file) {
                return $file->updated_at;
            })
            ->addColumn('action', function ($file) use ($course) {

                $btn = '<a href="javascript:void(0)" data-id="' .
                    $file->id . '" data-original-title="delete"
                                    data-url="' . route('admin.freeCourseLive.files.destroy', [$course, $file]) . '" class="ms-3 edit btn btn-danger btn-sm
                                    deleteFile"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->rawColumns(['file_path', 'action'])
            ->make(true);
    }

    public function storeFiles($request, Course $course, $storage)
    {
        $file_type = 'archivos';
        $category = 'cursos';
        $belongsTo = 'cursos';
        $relation = 'one_many';

        foreach ($request->file('files') as $file) {

            $stored = app(FileService::class)->store(
                $course,
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



    // ----------------- PARTICIPANT -----------------

    public function getFreeCoursesLive()
    {
        $user = Auth::user();

        $query = Course::where([
            'active' => 'S',
            'course_type' => 'LIVEFREECOURSE'
        ])->withMax(['events' => function ($q) {
            $q->doesntHave('courseModule');
        }], 'date')
        ->with([
                'file' => fn($q) => $q->where('file_type', 'imagenes'),
            ])
        ->has('events')
        ->whereHas('events', function ($q) {
            $q->doesntHave('courseModule');
        });

        if ($user->role == 'participants') {

            $query->whereHas('events.certifications', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with([
                'events' => fn($q2) =>
                    $q2->whereHas('certifications', function ($q3) use ($user) {
                        $q3->where('certifications.user_id', $user->id)
                            ->where('evaluation_type', 'certification');
                    })
                    ->with(
                        [
                            'user:id,name,paternal',
                            'certifications' => fn($q3) =>
                                $q3->where('certifications.user_id', $user->id)
                                    ->where('evaluation_type', 'certification')
                                    ->select('id', 'user_id', 'event_id', 'status', 'evaluation_type', 'score', 'score_fin')
                        ]
                    )
                    ->select('events.id', 'events.course_module_id', 'events.group_event_id', 'events.user_id')
                    ->where('events.active', 'S')
                    ->doesntHave('courseModule'),

                'courseCertifications' => fn($q) =>
                    $q->where([
                        'certifications.user_id' => $user->id,
                        'evaluation_type' => 'certification'
                    ])
            ])
            ->withCount([
                'courseCertifications as participants_count' => fn($q) =>
                    $q->select(DB::raw('count(distinct(certifications.user_id))'))
                        ->where('events.active', 'S'),
                'events as total_events_count' => fn($q) =>
                    $q->whereHas('certifications', function ($q) use ($user) {
                        $q->where('certifications.user_id', $user->id);
                    })
                    ->doesntHave('courseModule'),
                'events as completed_events_count' => fn($q) =>
                    $q->whereHas('certifications', function ($q) use ($user) {
                        $q->where('certifications.user_id', $user->id)
                            ->where('certifications.evaluation_type', 'certification')
                            ->where('certifications.status', 'finished');
                    })
                    ->doesntHave('courseModule'),
                'events as events_with_assignments_count' => fn($q) =>
                    $q->has('assignments')
                    ->doesntHave('courseModule')
            ]);
        }

        if ($user->role == 'instructor') {

            $query = $query->whereHas('events', function ($q) use ($user) {
                $q->where('events.user_id', $user->id)
                ->doesntHave('courseModule');
            })
                ->withCount([
                    'courseCertifications as participants_count' => function ($q) use ($user) {
                        $q->select(DB::raw('count(distinct(certifications.user_id))'))
                        ->where('events.user_id', $user->id)
                        ->where('events.active', 'S')
                        ->whereHas('event', function ($q) {
                                $q->doesntHave('courseModule');
                            }
                        );
                    }
                ]);
        }

        $freeCourses = $query->get()->sortByDesc('events.date');

        return $freeCourses ?? collect();
    }


    // INSTRUCTOR

    public function getParticipantsDataTable(User $user, Event $event)
    {
        $query = $event->certifications()
            ->whereHas('event', function ($q) use ($user) {
                $q->where('events.user_id', $user->id);
            })
            ->where('evaluation_type', 'certification')
            ->with('user', 'event')
            ->select('certifications.*');

        $allCertifications = DataTables::of($query)
            ->editColumn('status', function ($certification) {
                $status = $certification->status;
                $statusBtn = '<span class="status ' . $status . '">' .
                    config('parameters.certification_status')[$status]
                    . '</span>';

                return $statusBtn;
            })
            ->editColumn('assist_user', function ($certification) {
                $assit_btn = '<label class="custom-switch">
                                        <input type="checkbox" name="flg_asist" ' . $certification->event_assist_status . '
                                            class="custom-switch-input flg_assist_user_checkbox ' . $certification->event_assist_status . '"
                                            ' . $certification->valid_assist_checked . '
                                            data-url="' . route('events.certification.updateAssist', $certification) . '">
                                        <span class="custom-switch-indicator"></span>
                                    </label>';

                return $assit_btn;
            })
            ->editColumn('user.profile_user', function ($certification) {
                return $certification->user->profile_user ?? 'Pendiente';
            })
            ->editColumn('score_fin', function ($certification) {
                return $certification->score_fin ?? '-';
            })
            ->editColumn('action', function ($certification) {
                $btn = '<button data-toggle="modal" data-id="' . $certification->id . '"
                            data-send="' . route('aula.freeCourseLive.events.certification.getScoreFin', $certification) . '"
                            data-url="' . route('aula.freeCourseLive.events.certification.storeScoreFin', $certification) . '"
                            data-original-title="edit" class="me-3 edit btn btn-warning btn-sm showScoreFin">
                            <i class="fa-solid fa-star"></i>
                        </button>';

                return $btn;
            })
            ->rawColumns(['status', 'assist_user', 'enabled', 'score_fin', 'action'])
            ->make(true);

        return $allCertifications;
    }






}
