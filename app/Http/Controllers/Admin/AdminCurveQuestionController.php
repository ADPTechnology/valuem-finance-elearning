<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicQuestion;
use App\Models\Exam;
use App\Models\QuestionType;
use App\Services\DynamicQuestionService;
use Exception;
use Illuminate\Http\Request;

class AdminCurveQuestionController extends Controller
{
    private $dynamicQuestionService;

    public function __construct(DynamicQuestionService $service)
    {
        $this->dynamicQuestionService = $service;
    }

    public function index(Request $request, Exam $exam)
    {
        if ($request->ajax()) {
            return $this->dynamicQuestionService->getDataTableStep($exam->id);
        }

        $exam->loadRelationshipsStep();

        $questionTypes = QuestionType::get(['id', 'description']);

        return view('admin.curveStep.questions.index',
            compact(
                'exam',
                'questionTypes'
            )
        );
    }

    public function show(DynamicQuestion $question)
    {
        $question->loadRelationshipsStep();
        $questionType_id = $question->question_type_id;

        return view(
            'admin.curveStep.questions.show',
            compact(
                'question',
                'questionType_id'
            )
        );
    }

    public function update(Request $request, DynamicQuestion $question)
    {
        $question->loadRelationshipsStep();

        $success = true;
        $html = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $question = $this->dynamicQuestionService->update($request, $question, $storage);

            $question->loadRelationshipsStep();
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


    public function store(Request $request, Exam $exam)
    {
        $success = true;
        $html = null;
        $htmlQuestion = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $question = $this->dynamicQuestionService->store($request, $exam, $storage);

            $exam->loadRelationshipsStep();

            $html = view('admin.curveStep.questions.partials.exam-box-step', compact('exam'))->render();

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
                "html" => $html,
                "htmlQuestion" => $htmlQuestion
            ]
        ]);
    }


    public function destroy(Request $request, DynamicQuestion $question)
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

                $exam->loadRelationships();
                $html = view('admin.curveStep.questions.partials.exam-box-step', compact('exam'))->render();

                $message = config('parameters.deleted_message');
            } catch (Exception $e) {
                $success = false;
                $message = $e->getMessage();
            }

        } else {
            $success = false;
            $message = config('parameters.exception_message');
        }

        if ($request->has('place')) {
            $route = route('admin.forgettingCurve.steps.evaluation.showQuestions', $exam);
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route,
            "html" => $html
        ]);
    }




}
