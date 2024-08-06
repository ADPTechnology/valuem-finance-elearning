<?php

namespace App\Services;

use App\Models\{Exam};
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExamService
{
    public function getDataTable()
    {
        $allExams = DataTables::of(
            Exam::with([
                'ownerCompany:id,name',
                'course:id,description',
            ])
                ->withCount(['events', 'questions'])
                ->where('exam_type', 'dynamic')
        )
            ->editColumn('title', function ($exam) {
                return '<a href="' . route('admin.exams.showQuestions', $exam) . '">' . $exam->title . '</a>';
            })
            ->editColumn('exam_time', function ($exam) {
                return $exam->exam_time . ' min.';
            })
            ->editColumn('active', function ($exam) {
                $status = $exam->active;
                $statusBtn = '<span class="status ' . getStatusClass($status) . '">' . getStatusText($status) . '</span>';

                return $statusBtn;
            })
            ->addColumn('action', function ($exam) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $exam->id . '" data-url="' . route('admin.exams.update', $exam) . '"
                                        data-send="' . route('admin.exams.edit', $exam) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editExam-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $exam->events_count == 0 &&
                    $exam->questions_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $exam->id . '" data-original-title="delete"
                                            data-url="' . route('admin.exams.destroy', $exam) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteExam-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'active', 'action'])
            ->make(true);

        return $allExams;
    }

    public function store($request)
    {
        $data = normalizeInputStatus($request->validated());

        $exam = Exam::create($data + [
            "exam_type" => 'dynamic'
        ]);

        if ($exam) {
            return $exam;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, Exam $exam)
    {
        $data = normalizeInputStatus($request->validated());

        $isUpdated = $exam->update($data);

        if ($isUpdated) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(Exam $exam)
    {
        $isDeleted = $exam->delete();

        if ($isDeleted) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }


    // FREE COURSE EXAMS

    public function getDataTableExamForCourse(Course $course)
    {

        $exams = $course->exams()->withCount('questions', 'fcEvaluations');

        return DataTables::of($exams)
            ->editColumn('title', function ($exam) {
                return '<a href="' . route('admin.freeCourses.exams.show', $exam) . '">' . $exam->title . '</a>';
            })
            ->editColumn('duration', function ($exam) {
                return $exam->exam_time . ' min.';
            })
            ->editColumn('active', function ($exam) {

                $status = $exam->active;
                $statusBtn = '<span class="status ' . getStatusClass($status) . '">' . getStatusText($status) . '</span>';

                return $statusBtn;
            })
            ->editColumn('action', function ($exam) {

                $btn = '<button data-toggle="modal" data-id="' .
                    $exam->id . '" data-url="' . route('admin.freeCourses.exams.update', $exam) . '"
                                        data-send="' . route('admin.freeCourses.exams.edit', $exam) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editExam-btn"><i class="fa-solid fa-pen-to-square"></i></button>';

                if (
                    $exam->fc_evaluations_count == 0 &&
                    $exam->questions_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $exam->id . '" data-original-title="delete"
                                            data-url="' . route('admin.freeCourses.exams.delete', $exam) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteExam-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'active', 'action'])
            ->make(true);
    }

    public function storeExamForCourse(Request $request, Course $course)
    {
        $data = normalizeInputStatus($request->all());

        return $course->exams()->create($data + [
            "exam_type" => 'simple'
        ]);
    }


    public function updateExamForCourse(Request $request, Exam $exam)
    {

        $data = normalizeInputStatus($request->all());

        return $exam->update($data);
    }

    public function destroyExamForCourse(Exam $exam)
    {
        return $exam->delete();
    }
}
