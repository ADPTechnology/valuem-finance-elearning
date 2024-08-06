<?php

namespace App\Http\Controllers\Aula\Company;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\KeyPerformanceIndicatorsService;
use Illuminate\Http\Request;

class AulaKpisCompanyController extends Controller
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
            $data = $this->keyPerformanceIndicatorsService->getStatusEvaluations($idCourse);
            return response()->json($data);
        }

        $statusCertifications = $this->keyPerformanceIndicatorsService->getStatusEvaluations();
        $profile = $this->keyPerformanceIndicatorsService->getQtyProfileUser();
        $satisfaction = $this->keyPerformanceIndicatorsService->getFilterSatisfaction();


        $courses = Course::where('course_type', 'REGULAR')->get(['id', 'description']);
        return view('aula.company.kpisCompany.index', compact('statusCertifications', 'profile', 'courses', 'satisfaction'));
    }

    public function getSatisfactionKpi(Request $request)
    {
        $idCourse = (int)$request->course;
        $satisfaction = $this->keyPerformanceIndicatorsService->getFilterSatisfaction($idCourse);
        return response()->json($satisfaction);
    }

}
