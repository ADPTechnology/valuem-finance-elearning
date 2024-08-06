<?php

namespace App\Services;

use App\Models\{Course};
use Carbon\Carbon;
use DB;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class CourseService
{
    public function getDataTable()
    {
        $query = Course::withCount([
            'folders',
            'exams',
        ])
            ->with('type')
            ->where('course_type', 'REGULAR');
        // ->select('courses.*');

        $allCourses = DataTables::of($query)
            ->editColumn('course_type_id', function ($course) {
                return $course->type->name ?? '-';
            })
            ->editColumn('subtitle', function ($course) {
                return $course->subtitle ?? '-';
            })
            ->editColumn('description', function ($course) {
                return '<a href="' . route('admin.courses.show', $course) .
                    '" class="content-course-btn">' . $course->description . '</a>';
            })
            ->editColumn('time_start', function ($course) {
                return (Carbon::parse($course->time_start))->format('g:i A');
            })
            ->editColumn('time_end', function ($course) {
                return (Carbon::parse($course->time_end))->format('g:i A');
            })
            ->editColumn('active', function ($course) {
                $status = $course->active == 'S' ? 'active' : 'inactive';
                $txtBtn = $status == 'active' ? 'Activo' : 'Inactivo';
                $statusBtn = '<span class="status ' . $status . '">' . $txtBtn . '</span>';

                return $statusBtn;
            })
            ->addColumn('action', function ($course) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $course->id . '" data-url="' . route('admin.courses.update', $course) . '"
                                        data-send="' . route('admin.courses.edit', $course) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editCourse"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $course->exams_count == 0 &&
                    $course->folders_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $course->id . '" data-original-title="delete"
                                            data-url="' . route('admin.courses.delete', $course) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteCourse"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['description', 'active', 'action'])
            ->make(true);

        return $allCourses;
    }

    public function getCoursesBasedOnRole($user)
    {
        $courses = collect();

        if ($user->role == 'instructor') {
            $courses = Course::whereHas('exams.events', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('course_type', 'REGULAR');
            })
                ->with([
                    'file' => fn($query5) =>
                        $query5->where('file_type', 'imagenes')
                            ->where('category', 'cursos')
                            ->select('id', 'file_url', 'file_type', 'category', 'fileable_id', 'fileable_type')
                ])
                ->withCount([
                    'courseCertifications as users_course_count' => function ($query6) use ($user) {
                        $query6
                            ->select(DB::raw('count(distinct(certifications.user_id))'))
                            ->where('events.user_id', $user->id);
                    }
                ])
                ->withMax('events', 'date')
                ->where('active', 'S')
                ->get()
                ->sortByDesc('events_max_date');

        } else if ($user->role == 'participants') {

            $courses = Course::whereHas('exams.events.certifications', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                    ->where('course_type', 'REGULAR');
                })
                ->with([
                    'courseCertifications' => fn($q) =>
                        $q->where([
                            'certifications.user_id' => $user->id,
                            'status' => 'finished',
                            'evaluation_type' => 'certification'
                        ])
                        ->whereHas('event', function ($q3) {
                            $q3->whereRaw('certifications.score >= events.min_score')
                            ->doesntHave('courseModule');
                        }),
                    'exams' => fn($query2) =>
                        $query2->select('id', 'course_id')
                            ->whereHas('events.certifications', function ($query3) use ($user) {
                                $query3->where('user_id', $user->id);
                            })
                            ->with([
                                'events' => fn($query4) =>
                                    $query4->select('id', 'user_id', 'exam_id', 'min_score', 'questions_qty')
                                        ->whereHas('certifications', function ($query5) use ($user) {
                                            $query5->where('user_id', $user->id);
                                        })
                                        ->with([
                                            'user:id,name,paternal,maternal',
                                            'certifications' => fn($query6) =>
                                                $query6->where('evaluation_type', 'certification')
                                                    ->select(
                                                        'id',
                                                        'user_id',
                                                        'event_id',
                                                        'event_id',
                                                        'assist_user',
                                                        'status',
                                                        'score'
                                                    )
                                        ])
                                        ->doesntHave('courseModule')
                            ]),
                    'file' => fn($query7) =>
                        $query7->where('file_type', 'imagenes')
                            ->where('category', 'cursos')
                            ->select('id', 'file_url', 'file_type', 'category', 'fileable_id', 'fileable_type')
                ])
                ->withMax('events', 'date')
                ->where('active', 'S')
                ->get()
                ->sortByDesc('events_max_date');

        } else if (in_array($user->role, ['security_manager', 'security_manager_admin'])) {
            $courses = Course::whereHas('type', function ($q) {
                $q->whereRaw("(LOWER(course_types.name) LIKE '%inducci%')")
                    ->where('course_type', 'REGULAR');
            })
                ->with([
                    'file' => fn($q2) =>
                        $q2->where('file_type', 'imagenes')
                            ->where('category', 'cursos')
                            ->select('id', 'file_url', 'file_type', 'category', 'fileable_id', 'fileable_type'),
                    'events' => fn($q5) =>
                        $q5->whereYear('events.date', '=', date('Y'))
                            ->with('user:id,name,paternal')
                ])
                ->withCount([
                    'courseCertifications as users_course_count' => function ($q3) {
                        $q3->whereRaw('year(events.date) = "' . date('Y') . '"')
                            ->select(DB::raw('count(distinct(certifications.user_id))'));
                    }
                ])
                ->whereHas('events', function ($q4) {
                    $q4->where('events.active', 'S')
                        ->whereYear('events.date', '=', date('Y'));
                })
                ->withMax('events', 'date')
                ->where('active', 'S')
                ->get()
                ->sortByDesc('events_max_date');
        }

        return $courses;
    }

    public function store($request, $storage)
    {
        $data = normalizeInputStatus($request->validated());

        $time_start = Carbon::parse(Carbon::createFromFormat('g:i A', $data['time_start']))->format('H:i:s');
        $time_end = Carbon::parse(Carbon::createFromFormat('g:i A', $data['time_end']))->format('H:i:s');

        $data['time_start'] = $time_start;
        $data['time_end'] = $time_end;

        $courseModel = Course::create($data + [
            "course_type" => 'REGULAR'
        ]);

        if ($courseModel) {
            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'cursos';
                $belongsTo = 'cursos';
                $relation = 'one_one';

                $file = $request->file('image');

                return app(FileService::class)->store(
                    $courseModel,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $courseModel;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, $storage, Course $course)
    {
        $data = normalizeInputStatus($request->validated());

        $time_start = Carbon::parse(Carbon::createFromFormat('g:i A', $data['time_start']))->format('h:i:s');
        $time_end = Carbon::parse(Carbon::createFromFormat('g:i A', $data['time_end']))->format('h:i:s');

        $data['time_start'] = $time_start;
        $data['time_end'] = $time_end;

        $courseUpdated = $course->update($data);

        if ($courseUpdated) {
            if ($request->hasFile('image')) {

                app(FileService::class)->destroy($course->file, $storage);

                $file_type = 'imagenes';
                $category = 'cursos';
                $file = $request->file('image');
                $belongsTo = 'cursos';
                $relation = 'one_one';

                return app(FileService::class)->store(
                    $course,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }
            return $courseUpdated;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy($storage, Course $course)
    {
        app(FileService::class)->destroy($course->file, $storage);

        return $course->delete();
    }
}
