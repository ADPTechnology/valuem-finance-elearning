<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CourseTypeReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseType\CourseTypeRequest;
use App\Models\{CourseType};
use App\Services\CourseTypeService;
use Exception;
use Illuminate\Http\Request;

class CourseTypeController extends Controller
{
    private $coursetypeService;

    public function __construct(CourseTypeService $service)
    {
        $this->coursetypeService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->coursetypeService->getDataTable();
        }

        return view('admin.courseTypes.index');
    }

    public function store(CourseTypeRequest $request)
    {
        try {
            $this->coursetypeService->store($request);
            $success = true;
            $message = config('parameters.stored_message');
        } catch (Exception $e) {
            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function edit(CourseType $coursetype)
    {
        return response()->json([
            "coursetype" => $coursetype
        ]);
    }

    public function update(CourseTypeRequest $request, CourseType $coursetype)
    {
        try {
            $success = $this->coursetypeService->update($request, $coursetype);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function destroy(CourseType $coursetype)
    {
        try {
            $success = $this->coursetypeService->destroy($coursetype);
            $message = config('parameters.deleted_message');
        } catch (Exception $e) {
            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function exportExcel(Request $request)
    {
        $courseTypeExport = new CourseTypeReport;

        $courseType = CourseType::orderBy('id', 'desc')->limit(500)->get();

        $courseTypeExport->setCourseTypes($courseType);

        $date_info = 'últimos_500';

        return $courseTypeExport->download(
            'reporte-tipos-de-curso_' . $date_info . '.xlsx'
        );
    }

}
