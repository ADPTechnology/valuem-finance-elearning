<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\FcInstance;
use App\Models\FcStep;
use Illuminate\Http\Request;

class AulaFcInstanceController extends Controller
{
    public function show(FcInstance $fcInstance, Certification $certification)
    {

        $fcInstance->load('steps', 'forgettingCurve', 'steps.fcStepProgress');

        return view('aula.viewParticipant.forgettingCurve.instances.show', compact('fcInstance', 'certification'));
    }


    public function getInfoEvaluation(FcStep $fcStep)
    {

        $fcStep->load('exams');

        if ($fcStep->type == 'video') {
            $fcStep->load('video', 'video.questions');
            $html = view('aula.viewParticipant.forgettingCurve.evaluation.partials._video_evaluation', compact('fcStep'))->render();
        } else {
            $html = view('aula.viewParticipant.forgettingCurve.evaluation.partials._basic_evaluation', compact('fcStep'))->render();
        }

        return response()->json([
            'html' => $html,
            'fcStep' => $fcStep,
        ]);

    }

}
