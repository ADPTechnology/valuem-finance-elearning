<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\FcStepProgress;
use App\Models\FcVideo;
use App\Services\FcEvaluationVideoService;
use Illuminate\Http\Request;

class AulaFcVideoController extends Controller
{

    protected $fcEvVideoService;

    public function __construct(FcEvaluationVideoService $fcEvVideo)
    {
        $this->fcEvVideoService = $fcEvVideo;
    }

    public function updateVideo(FcStepProgress $fcStepProgress, FcVideo $video, $num_question, $key, $evaluation)
    {
        if ($fcStepProgress->step->type == 'video') {
            return $this->fcEvVideoService->update($fcStepProgress, $video, $num_question, $key, $evaluation);
        }
    }


}
