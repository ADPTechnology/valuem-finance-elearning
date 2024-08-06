<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicQuestion;
use App\Models\FcVideo;
use App\Models\FcVideoQuestion;
use App\Services\DynamicQuestionService;
use App\Services\FcVideoService;
use Exception;
use Illuminate\Http\Request;

class FcVideoQuestionController extends Controller
{

    protected $fcVideoService;
    private $dynamicQuestionService;

    public function __construct(FcVideoService $serviceVideo, DynamicQuestionService $service)
    {
        $this->dynamicQuestionService = $service;
        $this->fcVideoService = $serviceVideo;

    }

    public function getDatatable(Request $request, FcVideo $video)
    {
        if ($request->ajax()) {
            return $this->fcVideoService->getDatatableQuestions($video);
        }

    }


    public function store(Request $request, FcVideo $video)
    {

        $storage = env('FILESYSTEM_DRIVER');
        $success = true;

        try {

            $question = $this->fcVideoService->storeQuestion($request, $video, $storage);
            $htmlQuestion = $this->dynamicQuestionService->getQuestionTypeView(null, $question->question_type_id);
            $video->loadRelationShip();
            $html = view('admin.curveStep.partials.components._video', compact('video'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'stored') : config('parameters.exception_message');

        return response()->json([
            'success' => $success,
            'message' => $message,
            "data_show" => [
                "html" => $html,
                "htmlQuestion" => $htmlQuestion
            ]
        ]);

    }

    public function show(FcVideo $video, DynamicQuestion $question)
    {
        $question->load(['alternatives', 'fcVideo', 'fcVideo.step', 'fcVideo.step.instance', 'fcVideo.step.instance.forgettingCurve']);

        $questionType_id = $question->question_type_id;

        return view(
            'admin.curveStep.questions.video.show',
            compact(
                'question',
                'questionType_id'
            )
        );
    }

    public function update(Request $request, DynamicQuestion $question)
    {
        $question->load(['alternatives', 'droppableOptions', 'fcVideo', 'fcVideo.step', 'fcVideo.step.instance', 'fcVideo.step.instance.forgettingCurve']);

        $success = true;
        $html = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {

            $question = $this->dynamicQuestionService->update($request, $question, $storage);

            $question->load(['alternatives', 'droppableOptions', 'fcVideo', 'fcVideo.step', 'fcVideo.step.instance', 'fcVideo.step.instance.forgettingCurve']);

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


    public function destroy(DynamicQuestion $question)
    {

        $question->loadRelationshipsVideo();
        $video = $question->fcVideo;


        $success = true;
        $route = null;
        $html = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {

            $success = $this->fcVideoService->destroyQuestion($question, $storage);
            $video->loadRelationShip();
            $html = view('admin.curveStep.partials.components._video', compact('video'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'deleted') : config('parameters.exception_message');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);
    }


}
