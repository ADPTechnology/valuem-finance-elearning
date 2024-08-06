<?php

namespace App\Http\Controllers\Aula\Instructor;

use App\Exports\SurveyProfileExport;
use App\Exports\UserSurveyExport;
use App\Http\Controllers\Controller;
use App\Services\Reports\ProfileSurveyService;
use App\Services\Reports\UserSurveyService;
use Illuminate\Http\Request;
use Auth;

class AulaUserSurveyInstructorController extends Controller
{
    private $profileSurveyService;
    private $userSurveyService;

    public function __construct(ProfileSurveyService $profileSurveyService, UserSurveyService $userSurveyService)
    {
        $this->profileSurveyService = $profileSurveyService;
        $this->userSurveyService = $userSurveyService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $user = Auth::user();

            if ($request->has('survey') && $request['survey'] == 'user_profile') {

                return $this->profileSurveyService->getCompanyUserProfileSurveysDataTable($request, $user);
            }
        }


        return view('aula.instructor.userSurvey.index');
    }


    public function downloadExcelUserSurveys(Request $request)
    {
        $user = Auth::user();

        $surveyProfileExport = new UserSurveyExport;

        $surveyProfileExport->setUserSurveys($this->userSurveyService->getByFiltersAndCompany($request, 'evaluation', $user));

        $date_info = 'todos_los_registros';

        return $surveyProfileExport->download(
            'reporte-perfil-usuario_' . $date_info . '.xlsx'
        );
    }


    public function downloadExcelUserProfile(Request $request)
    {
        $user = Auth::user();

        $surveyProfileExport = new SurveyProfileExport;

        $surveyProfileExport->setUserSurveys($this->userSurveyService->getByFiltersAndCompany($request, 'user_profile', $user));

        $date_info = 'todos_los_registros';

        return $surveyProfileExport->download(
            'reporte-perfil-usuario_' . $date_info . '.xlsx'
        );
    }



}
