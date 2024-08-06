<?php
namespace App\Http\Controllers\Aula\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Exports\UserExportEvaluations;
use App\Models\Course;
use App\Services\CompanyService;
use Auth;
use Illuminate\Http\Request;

class AulaUserEvaluationsCompanyController  extends Controller
{
    private $userEvaluationsCompanyService;
    public function __construct(CompanyService $service)
    {
        $this->userEvaluationsCompanyService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $company = Auth::user()->company;
            return $this->userEvaluationsCompanyService->getEvaluationsCompanyAll($request, $company);
        }

        $companies = Company::get(['id', 'description']);
        $courses = Course::where('course_type', 'REGULAR')->get(['id', 'description']);

        return view('aula.company.evaluationsCompany.index', compact('companies', 'courses'));
    }


    public function downloadExcelEvaluations(Request $request)
    {

        $company = Auth::user()->company;

        $userEvaluationsCompany = new UserExportEvaluations;

        $userEvaluationsCompany->setEvaluations(app(CompanyService::class)->getEvaluationsCompany($request, $company));

        $date_info = 'todos_los_registros';

        return $userEvaluationsCompany->download(
            'evaluaciones-por-participantes_'. $company->description . '_' . $date_info .'.xlsx'
        );
    }

}
