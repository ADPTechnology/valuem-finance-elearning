<?php

namespace App\Services;

use App\Models\{CourseType};
use Carbon\Carbon;
use DB;
use Exception;
use Request;
use Yajra\DataTables\Facades\DataTables;

class CourseTypeService
{
    public function getDataTable()
    {
        $query = CourseType::withCount('courses');

        $allCoursestypes = DataTables::of($query)
            ->editColumn('created_at', function ($coursetype) {
                return $coursetype->created_at;
            })
            ->editColumn('updated_at', function ($coursetype) {
                return $coursetype->updated_at;
            })
            ->addColumn('action', function ($coursetype) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $coursetype->id . '" data-url="'. route('admin.coursetypes.update', $coursetype) .'"
                                        data-send="'. route('admin.coursetypes.edit', $coursetype) .'"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editCoursetype"><i class="fa-solid fa-pen-to-square"></i></button>';
                if ($coursetype->courses_count == 0)
                {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $coursetype->id . '" data-original-title="delete"
                                            data-url="'. route('admin.coursetypes.destroy', $coursetype) .'" class="ms-3 edit btn btn-danger btn-sm
                                            deleteCoursetype"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);

        return $allCoursestypes;
    }

    public function store($request)
    {
        $courseType = CourseType::create($request->validated());

        if ($courseType) {
            return $courseType;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, $coursetype)
    {
        if ($coursetype->update($request->validated())) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(CourseType $coursetype)
    {
        if ($coursetype->delete()) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

}
