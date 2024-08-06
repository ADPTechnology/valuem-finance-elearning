<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FcStep;
use App\Models\FcVideo;
use App\Models\QuestionType;
use App\Services\FcVideoService;
use Exception;
use Illuminate\Http\Request;

class FcVideoController extends Controller
{

    protected $fcVideoService;

    public function __construct(FcVideoService $service)
    {
        $this->fcVideoService = $service;
    }


    public function upload(Request $request, FcStep $step)
    {
        try {

            $storage = env('FILESYSTEM_DRIVER');

            $success = $this->fcVideoService->store($request, $step, $storage);

            $step->loadRelationShipVideo();

            $questionTypes = QuestionType::get(['id', 'description']);

            $html = view('admin.curveStep.partials.components._stepVideo', compact('step', 'questionTypes'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'stored') : config('parameters.exception_message');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);

    }

    public function destroy(FcVideo $video)
    {

        try {

            $storage = env('FILESYSTEM_DRIVER');
            $step = $video->step;
            $success = $this->fcVideoService->destroy($video, $storage);
            $step->loadRelationShipVideo();
            $html = view('admin.curveStep.partials.components._stepVideo', compact('step'))->render();


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
