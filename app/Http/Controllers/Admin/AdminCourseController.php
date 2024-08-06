<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CourseReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{Course, CourseType};
use App\Services\{CourseService};
use Exception;

class AdminCourseController extends Controller
{
    private $courseService;

    public function __construct(CourseService $service)
    {
        $this->courseService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->courseService->getDataTable();
        }

        $coursetypes = CourseType::get(['id', 'name']);

        return view('admin.courses.index', compact('coursetypes'));
    }

    public function store(CourseRequest $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->courseService->store($request, $storage);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function edit(Course $course)
    {
        $course->loadCourseImage();

        $url_img = verifyImage($course->file);

        return response()->json([
            "id" => $course->id,
            "name" => $course->description,
            "subtitle" => $course->subtitle,
            "date" => $course->date,
            "hours" => $course->hours,
            "time_start" => (Carbon::parse($course->time_start))->format('g:i A'),
            "time_end" => (Carbon::parse($course->time_end))->format('g:i A'),
            "url_img" => $url_img,
            "status" => $course->active,
            "course_type_id" => $course->course_type_id,
        ]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->loadCourseImage();
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->courseService->update($request, $storage, $course);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function destroy(Course $course)
    {
        $course->loadCourseImage();
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->courseService->destroy($storage, $course);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function show(Course $course)
    {
        $course->loadMissing(
            [
                'file' => fn($query) =>
                    $query->where('file_type', 'imagenes')
                        ->where('category', 'cursos'),

                'folders' => fn($query2) =>
                    $query2->where('level', 1),

                'type'
            ],
        );

        $folders = $course->folders;

        return view('admin.courses.show', [
            'course' => $course,
            'folders' => $folders
        ]);
    }

    public function exportExcel(Request $request)
    {
        $courseExport = new CourseReport;

        $courses = Course::with('type:id,name')->orderBy('id', 'desc')->limit(500)->get();

        $courseExport->setCourses($courses);

        $date_info = 'últimos_500';

        return $courseExport->download(
            'reporte-cursos_' . $date_info . '.xlsx'
        );
    }

}
