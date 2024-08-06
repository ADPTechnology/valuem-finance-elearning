<?php

namespace App\Http\Controllers\Aula\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\KeyPerformanceIndicatorsService;
use Illuminate\Http\Request;

class SupervisorKpiController extends Controller
{


    protected $keyPerformanceIndicatorsService;

    public function __construct(KeyPerformanceIndicatorsService $service)
    {
        $this->keyPerformanceIndicatorsService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $idCourse = $request->course_id;
            $data = $this->keyPerformanceIndicatorsService->getStatusEvaluationsSupervisor($idCourse);
            return response()->json($data);
        }

        $statusCertifications = $this->keyPerformanceIndicatorsService->getStatusEvaluationsSupervisor();

        $profile = $this->keyPerformanceIndicatorsService->getQtyProfileUser(true);
        $satisfaction = $this->keyPerformanceIndicatorsService->getFilterSatisfaction(true);

        $courses = Course::where('course_type', 'REGULAR')->get(['id', 'description']);

        return view('aula.supervisor.kpisCompany.index', compact('statusCertifications', 'profile', 'courses', 'satisfaction'));
    }

    public function getSatisfactionKpi(Request $request)
    {
        $idCourse = (int) $request->course;
        $satisfaction = $this->keyPerformanceIndicatorsService->getFilterSatisfaction($idCourse, true);
        return response()->json($satisfaction);
    }


}
