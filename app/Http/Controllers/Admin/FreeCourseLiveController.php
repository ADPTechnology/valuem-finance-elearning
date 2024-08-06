<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FreeCourseLiveReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\{LiveFreeCourseRequest};
use App\Models\Course;
use App\Models\File;
use App\Services\{FileService, FreeCourseLiveService};
use Exception;
use Illuminate\Http\Request;

class FreeCourseLiveController extends Controller
{
    private $freeCourseLiveService;

    public function __construct(FreeCourseLiveService $service)
    {
        $this->freeCourseLiveService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->freeCourseLiveService->getDataTable();
        }

        return view('admin.live-free-courses.index');
    }

    public function show(Course $course)
    {
        $course->loadCourseImage();
        $course->loadCount([
            'files' => function ($q) {
                $q->where('file_type', 'archivos');
            },
            'exams'
        ]);

        return view('admin.live-free-courses.show',
            compact(
                'course'
            )
        );
    }

    public function store(LiveFreeCourseRequest $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $course = $this->freeCourseLiveService->store($request, $storage);
            $success = $course ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        $route = $request['verifybtn'] == 'show' ? route('admin.freeCourseLive.show', $course) : null;
        $show = $route ? true : false;

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route,
            "show" => $show,
        ]);
    }

    public function edit(Course $course)
    {
        $course->loadCourseImage();
        $course->time_start = getTimeforHummans($course->time_start);
        $course->time_end = getTimeforHummans($course->time_end);

        return response()->json([
            'course' => $course,
            'image' => verifyImage($course->file)
        ]);
    }

    public function update(LiveFreeCourseRequest $request, Course $course)
    {
        $storage = env('FILESYSTEM_DRIVER');
        $course->loadCourseImage();

        $html = null;
        $show = false;

        try {
            $course = $this->freeCourseLiveService->update($request, $course, $storage);
            $success = $course ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($request['place'] == 'show') {
            $course->loadCount([
                'files' => function ($q) {
                    $q->where('file_type', 'archivos');
                },
                'exams'
            ]);
            $course->loadCourseImage();
            $html = view('admin.live-free-courses.partials.components._course_box', compact('course'))->render();
            $show = true;
        }

        return response()->json([
            "message" => $message,
            "success" => $success,
            "html" => $html,
            "show" => $show,
            'title' => mb_strtolower($course->description, 'UTF-8'),
        ]);
    }

    public function destroy(Request $request, Course $course)
    {
        $course->loadCourseImage();
        $storage = env('FILESYSTEM_DRIVER');

        $show = false;
        $route = null;

        try {
            $success = $this->freeCourseLiveService->destroy($course, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        if ($request['place'] == 'show') {
            $route = route('admin.freeCourseLive.index');
            $show = true;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'route' => $route,
            'show' => $show,
        ]);
    }



    // -------------- FILES ---------------

    public function getFilesDataTable(Course $course)
    {
        return $this->freeCourseLiveService->getFilesDataTable($course);
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');
        return app(FileService::class)->download($file, $storage);
    }

    public function storeFiles(Request $request, Course $course)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->freeCourseLiveService->storeFiles($request, $course, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        $course->loadCount([
            'files' => function ($q) {
                $q->where('file_type', 'archivos');
            },
            'exams'
        ]);
        $course->loadCourseImage();
        $html = view('admin.live-free-courses.partials.components._course_box', compact('course'))->render();

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }

    public function destroyFile(Course $course, File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = app(FileService::class)->destroy($file, $storage);
        $message = getMessageFromSuccess($success, 'deleted');

        $course->loadCount([
            'files' => function ($q) {
                $q->where('file_type', 'archivos');
            },
            'exams'
        ]);
        $course->loadCourseImage();
        $html = view('admin.live-free-courses.partials.components._course_box', compact('course'))->render();

        return response()->json([
            "success" => true,
            "message" => $message,
            "html" => $html
        ]);
    }


    // EXPORT EXCEL

    public function exportExcel(Request $request)
    {
        $freeCourseLiveExport = new FreeCourseLiveReport;

        $freeCoursesLive = Course::orderBy('id', 'desc')->where('course_type', 'LIVEFREECOURSE')->limit(500)->get();

        $freeCourseLiveExport->setFreeCourseLive($freeCoursesLive);

        $date_info = 'últimos_500';

        return $freeCourseLiveExport->download(
            'reporte-cursos-libres-en-vivo_' . $date_info . '.xlsx'
        );
    }

}
