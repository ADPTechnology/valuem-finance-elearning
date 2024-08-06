<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\SpecCourse;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class AulaSpecEvaluationController extends Controller
{
    private $evaluationService;

    public function __construct(EvaluationService $service)
    {
        $this->evaluationService = $service;
    }

    public function index(SpecCourse $specCourse)
    {
        $modules = $this->evaluationService->getModulesFromSpecCourse($specCourse);

        return view('aula.viewParticipant.specCourses.evaluations.index', compact(
            'specCourse',
            'modules'
        ));
    }
}
