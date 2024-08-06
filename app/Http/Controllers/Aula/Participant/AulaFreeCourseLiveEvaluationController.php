<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class AulaFreeCourseLiveEvaluationController extends Controller
{
    private $evaluationService;

    public function __construct(EvaluationService $service)
    {
        $this->evaluationService = $service;
    }


    public function index(Course $course)
    {

        $certifications = $this->evaluationService->getEventsForCourseFreeLive($course);

        return view('aula.common.live-free-courses.onlinelessons.evaluations.index',
            compact(
                'course',
                'certifications'
            )
        );
    }
    
}
