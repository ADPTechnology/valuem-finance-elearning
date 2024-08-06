<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamRequest;
use App\Models\Course;
use App\Models\DynamicQuestion;
use App\Models\Exam;
use App\Models\QuestionType;
use App\Services\DynamicQuestionService;
use App\Services\ExamService;
use Exception;
use Illuminate\Http\Request;

class AdminCourseExamsController extends Controller
{

    private $examService;
    private $dynamicQuestionService;

    public function __construct(ExamService $examService, DynamicQuestionService $dynamicService)
    {
        $this->examService = $examService;
        $this->dynamicQuestionService = $dynamicService;
    }

    public function getDataTable(Course $course)
    {
        return $this->examService->getDataTableExamForCourse($course);
    }

    public function show(Exam $exam, Request $request)
    {

        if ($request->ajax()) {
            return $this->dynamicQuestionService->getDataTableFreeCourse($exam->id);
        }

        $course = $exam->course->loadFreeCourseRelationships();

        $questionTypes = QuestionType::get(['id', 'description']);

        return view('admin.free-courses.exams.index', compact('exam', 'course', 'questionTypes'));
    }


    public function showQuestion(DynamicQuestion $question)
    {

        $question->loadRelationShipFreeCourse();

        $questionType_id = $question->question_type_id;

        return view(
            'admin.free-courses.exams.questions.show',
            compact(
                'question',
                'questionType_id'
            )
        );
    }

    public function store(Request $request, Course $course)
    {
        try {
            $success = $this->examService->storeExamForCourse($request, $course);
        } catch (Exception $e) {

            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $success = true;
        $html = null;
        $htmlQuestion = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $question = $this->dynamicQuestionService->store($request, $exam, $storage);

            // $exam->loadRelationshipsStep();

            // $html = view('admin.curveStep.questions.partials.exam-box-step', compact('exam'))->render();

            $htmlQuestion = $this->dynamicQuestionService->getQuestionTypeView(null, $question->question_type_id);

            $message = config('parameters.stored_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "data_show" => [
                "html" => null,
                "htmlQuestion" => $htmlQuestion
            ]
        ]);
    }

    public function edit(Exam $exam)
    {
        return response()->json([
            'exam' => $exam
        ]);
    }

    public function update(Request $request, Exam $exam)
    {
        try {

            $success = $this->examService->updateExamForCourse($request, $exam);
        } catch (Exception $e) {

            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function updateQuestion(Request $request, DynamicQuestion $question)
    {
        // $question->loadRelationshipsStep();

        $success = true;
        $html = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $question = $this->dynamicQuestionService->update($request, $question, $storage);

            // $question->loadRelationshipsStep();
            $html = $this->dynamicQuestionService->getQuestionTypeView($question, $question->question_type_id);

            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html,
            "statement" => $question->statement
        ]);
    }


    public function destroy(Exam $exam)
    {
        try {
            $success = $this->examService->destroyExamForCourse($exam);
        } catch (Exception $e) {

            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function destroyQuestion(Request $request, DynamicQuestion $question)
    {
        $question->loadRelationships();
        $exam = $question->exam;

        $success = true;
        $route = null;
        $html = null;

        $storage = env('FILESYSTEM_DRIVER');

        if ($question->evaluations_count == 0) {

            try {

                $this->dynamicQuestionService->destroy($question, $storage);

                // $exam->loadRelationships();
                // $html = view('admin.curveStep.questions.partials.exam-box-step', compact('exam'))->render();
                $html = null;

                $message = config('parameters.deleted_message');
            } catch (Exception $e) {
                $success = false;
                $message = $e->getMessage();
            }
        } else {
            $success = false;
            $message = config('parameters.exception_message');
        }

        // if ($request->has('place')) {
        //     $route = route('admin.forgettingCurve.steps.evaluation.showQuestions', $exam);
        // }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route,
            "html" => $html
        ]);
    }
}
