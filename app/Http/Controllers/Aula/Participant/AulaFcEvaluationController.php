<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Exam;
use App\Models\FcStep;
use App\Models\FcStepProgress;
use App\Models\FcVideo;
use App\Services\FcEvaluationReinforcementService;
use App\Services\FcEvaluationService;
use App\Services\FcEvaluationVideoService;

class AulaFcEvaluationController extends Controller
{

    protected $fcEvReinforcementService;
    protected $fcEvaluationService;
    protected $fcEvVideoService;

    public function __construct(FcEvaluationReinforcementService $fcEvR, FcEvaluationService $fcEv, FcEvaluationVideoService $fcEvVideo)
    {
        $this->fcEvReinforcementService = $fcEvR;
        $this->fcEvaluationService = $fcEv;
        $this->fcEvVideoService = $fcEvVideo;
    }

    public function start(FcStep $step, FcStepProgress $fcStepProgress)
    {

        if ($step->type == 'reinforcement') {
            return $this->fcEvReinforcementService->start($fcStepProgress, $step);
        }
        if ($step->type == 'evaluation') {
            return $this->fcEvaluationService->start($fcStepProgress, $step);
        }
        if ($step->type == 'video') {
            return $this->fcEvVideoService->start($fcStepProgress, $step);
        }

    }

    public function show(FcStepProgress $fcStepProgress, $num_question)
    {

        if ($fcStepProgress->step->type == 'reinforcement') {
            return $this->fcEvReinforcementService->show($fcStepProgress, $num_question);
        }
        if ($fcStepProgress->step->type == 'evaluation') {
            return $this->fcEvaluationService->show($fcStepProgress, $num_question);
        }
        if ($fcStepProgress->step->type == 'video') {
            return $this->fcEvVideoService->show($fcStepProgress, $num_question);
        }
    }

    public function showVideo(FcStepProgress $fcStepProgress, FcStep $step)
    {

        return $this->fcEvVideoService->showVideo($fcStepProgress, $step);

    }

    public function update(FcStepProgress $fcStepProgress, Exam $exam, $num_question, $key, $evaluation)
    {

        if ($fcStepProgress->step->type == 'reinforcement') {
            return $this->fcEvReinforcementService->update($fcStepProgress, $exam, $num_question, $key, $evaluation);
        }
        if ($fcStepProgress->step->type == 'evaluation') {
            return $this->fcEvaluationService->update($fcStepProgress, $exam, $num_question, $key, $evaluation);
        }

    }

    public function updateVideo(FcStepProgress $fcStepProgress, FcVideo $video, $num_question, $key, $evaluation)
    {
        if ($fcStepProgress->step->type == 'video') {
            return $this->fcEvVideoService->update($fcStepProgress, $video, $num_question, $key, $evaluation);
        }
    }


    public function alternativeCorrect(FcStep $step, Certification $certification)
    {
        $step->loadRelationShipExam();
        $fcInstance = $step->instance;

        $exam = $step->exams->first();

        $questions = $exam->questions()->where('active', 'S')
            ->with([
                'alternatives' => function ($q) {
                    $q->with('droppableOption');
                }
            ])->get();

        return view('aula.viewParticipant.forgettingCurve.evaluation._show_alternative', compact('step', 'questions', 'exam', 'fcInstance', 'certification'));
    }


}
