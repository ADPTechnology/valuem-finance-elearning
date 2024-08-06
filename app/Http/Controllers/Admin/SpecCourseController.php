<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SpecCourseReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Requests\SpecCourse\SpecCourseRequest;
use App\Models\{SpecCourse, File};
use App\Services\FileService;
use App\Services\SpecCourseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class SpecCourseController extends Controller
{
    private $specCourseService;

    public function __construct(SpecCourseService $service)
    {
        $this->specCourseService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->specCourseService->getDataTable();
        }

        return view('admin.specCourses.index');
    }

    public function show(SpecCourse $specCourse)
    {
        $specCourse->loadRelationships();

        $moduleActive = '';

        return view(
            'admin.specCourses.show',
            compact(
                'specCourse',
                'moduleActive'
            )
        );
    }

    public function store(SpecCourseRequest $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $specCourse = $this->specCourseService->store($request, $storage);
            $success = $specCourse ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        $route = $request['verifybtn'] == 'show' ? route('admin.specCourses.show', $specCourse) : null;
        $show = $route ? true : false;

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route,
            "show" => $show,
        ]);
    }

    public function edit(SpecCourse $specCourse)
    {
        $specCourse->loadImage();
        $specCourse->time_start = getTimeforHummans($specCourse->time_start);
        $specCourse->time_end = getTimeforHummans($specCourse->time_end);
        $url_image = verifyImage($specCourse->file);

        return response()->json([
            "specCourse" => $specCourse,
            "url_image" => $url_image
        ]);
    }

    public function update(SpecCourseRequest $request, SpecCourse $specCourse)
    {
        $specCourse->loadImage();
        $storage = env('FILESYSTEM_DRIVER');

        $html = null;
        $show = false;

        try {
            $success = $this->specCourseService->update($request, $specCourse, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($request['place'] == 'show') {
            $specCourse->loadCounts();
            $specCourse->loadImage();
            $html = view('admin.specCourses.partials.components._specCourse_box', compact('specCourse'))->render();
            $show = true;
        }

        return response()->json([
            "success" => $success,
            'message' => $message,
            'show' => $show,
            'html' => $html,
            'title' => mb_strtolower($specCourse->title, 'UTF-8'),
        ]);
    }

    public function destroy(Request $request, SpecCourse $specCourse)
    {
        $specCourse->loadImage();
        $storage = env('FILESYSTEM_DRIVER');

        $show = false;
        $route = null;

        try {
            $success = $this->specCourseService->destroy($specCourse, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        if ($request['place'] == 'show') {
            $route = route('admin.specCourses.index');
            $show = true;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'route' => $route,
            'show' => $show,
        ]);
    }


    // ****---- FILES ----**** //

    public function showFiles(SpecCourse $specCourse)
    {

        $filesCourse = $specCourse->files()->where('file_type', 'archivos');

        return DataTables::of($filesCourse)
            ->addColumn('filename', function ($file) {
                return '<a href="' . route('admin.specCourses.file.download', $file) . '">' .
                    basename($file->file_path)
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
            ->addColumn('action', function ($file) use ($specCourse) {

                $btn = '<a href="javascript:void(0)" data-id="' .
                    $file->id . '" data-original-title="delete"
                                    data-url="' . route('admin.specCourses.file.destroy', ['file' => $file, 'specCourse' => $specCourse]) . '" class="ms-3 edit btn btn-danger btn-sm
                                    deleteFile"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->rawColumns(['filename', 'action'])
            ->make(true);

    }

    public function storeFile(Request $request, SpecCourse $specCourse)
    {

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->specCourseService->storeFiles($request, $specCourse, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $specCourse->loadCounts();
        $specCourse->loadImage();

        $html = view('admin.specCourses.partials.components._specCourse_box', compact('specCourse'))->render();
        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);


    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');
        return app(FileService::class)->download($file, $storage);
    }

    public function destroyFile(File $file, SpecCourse $specCourse)
    {

        $storage = env('FILESYSTEM_DRIVER');

        $success = app(FileService::class)->destroy($file, $storage);

        $specCourse->loadImage();
        $specCourse->loadCounts();

        $html = view('admin.specCourses.partials.components._specCourse_box', compact('specCourse'))->render();

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }


    // EXPORT EXCEL

    public function exportExcel(Request $request)
    {
        $specCourseExport = new SpecCourseReport;

        $specCourses = SpecCourse::orderBy('id', 'desc')->limit(500)->get();

        $specCourseExport->setSpecCourses($specCourses);

        $date_info = 'últimos_500';

        return $specCourseExport->download(
            'reporte-cursos-de-especialización_' . $date_info . '.xlsx'
        );
    }

}
