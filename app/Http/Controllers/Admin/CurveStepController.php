<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\FcStep;
use App\Models\OwnerCompany;
use App\Models\QuestionType;
use App\Services\CurveStepService;
use App\Services\ExamService;
use Exception;
use Illuminate\Http\Request;

class CurveStepController extends Controller
{

    protected $curveStepService;
    protected $examService;

    public function __construct(CurveStepService $service, ExamService $examService)
    {
        $this->curveStepService = $service;
        $this->examService = $examService;
    }

    public function show(FcStep $step)
    {

        if ($step->type == 'video') {
            $step->loadRelationShipVideo();
        } else {
            $step->loadRelationShipExam();
        }

        $questionTypes = QuestionType::get(['id', 'description']);

        return view('admin.curveStep.show', compact('step', 'questionTypes'));

    }

    public function edit(FcStep $step)
    {

        $instance = $step->instance;

        $step->loadImage();

        $url_img = verifyImage($step->file);

        $orders_steps = $instance->steps()
            ->select('id', 'order')
            ->orderBy('order', 'ASC')
            ->get();

        return response()->json([
            'step' => $step,
            'orders_steps' => $orders_steps,
            "url_img" => $url_img,
        ]);

    }

    public function update(FcStep $step, Request $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $step->loadImage();

        try {

            $success = $this->curveStepService->update($step, $request, $storage);

        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'updated') : config('parameters.exception_message');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);

    }


    public function registerExam(Request $request, FcStep $step)
    {
        try {

            $success = $this->curveStepService->storeExam($request, $step);
            $step->loadRelationShipExam();
            $html = view('admin.curveStep.partials.components._evaluation_main', compact('step'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'stored') : config('parameters.exception_message');

        return response()->json([
            "success" => $success,
            "message" => $message,
            'html' => $html
        ]);

    }

    public function deleteExam(Exam $exam)
    {
        try {

            $success = $this->examService->destroy($exam);
            $step = $exam->fcStep->loadRelationShipExam();
            $html = view('admin.curveStep.partials.components._evaluation_main', compact('step'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'deleted') : config('parameters.exception_message');

        return response()->json([
            "success" => $success,
            "message" => $message,
            'html' => $html
        ]);
    }

    public function editExam(Exam $exam)
    {
        $ownerCompanies = OwnerCompany::get(['id', 'name']);

        $exam->load('course');

        return response()->json([
            "exam" => $exam,
            "ownerCompanies" => $ownerCompanies
        ]);
    }

    public function updateExam(Request $request, Exam $exam)
    {
        try {

            $active = $request->active == 'on' ? 'S' : 'N';

            $data = [
                "title" => $request->title,
                "exam_time" => $request->exam_time,
                "active" => $active,
            ];

            $success = $exam->update($data);

            $step = $exam->fcStep->loadRelationShipExam();
            $html = view('admin.curveStep.partials.components._evaluation_main', compact('step'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'updated') : config('parameters.exception_message');

        return response()->json([
            "success" => $success,
            "message" => $message,
            'html' => $html
        ]);
    }



}
