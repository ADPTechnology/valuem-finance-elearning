<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\{Certification, Course};
use App\Services\EvaluationService;

class AulaEvaluationController extends Controller
{
    private $evaluationService;

    public function __construct(EvaluationService $service)
    {
        $this->evaluationService = $service;
    }

    public function index(Course $course)
    {
        $certifications = $this->evaluationService->getCertificationsFromCourse($course);

        return view('aula.viewParticipant.courses.evaluations.index', [
            'certifications'=> $certifications,
            'course' => $course
        ]);
    }



    public function getAjaxCertification(Certification $certification)
    {
        $event = $certification->event->load('exam');
        $exam = $event->exam;
        $n_questions = $event->questions_qty;

        return response()->json([
            'total_time' => $exam->exam_time,
            'question_time' => $exam->exam_time / $n_questions
        ]);
    }

}
