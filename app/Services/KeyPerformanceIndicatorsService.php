<?php

namespace App\Services;

use App\Models\Certification;
use App\Models\User;
use App\Models\UserSurvey;
use Auth;
use Carbon\Carbon;

class KeyPerformanceIndicatorsService
{

    private $PROFILES = ['DIVERGENTE', 'CONVERGENTE', 'ASIMILADOR', 'ACOMODADOR'];
    private $SATISFACTION = ['1. Muy Insatisfecho', 'Muy insatisfecho', '2. Insatisfecho', '3. Ni Satisfecho / Ni Insatisfecho', 'Ni Satisfecho / Ni Insatisfecho', '4. Satisfecho'];

    private function getFinishedCertifications($idCourse = NULL)
    {
        $idCompany = Auth::user()->company_id;
        $start = Carbon::now()->startOfMonth();


        return collect([]);

    }

    public function getStatusEvaluations($idCourse = NULL)
    {

        $finishedCertifications = $this->getFinishedCertifications($idCourse);

        $approved = $finishedCertifications->filter(function ($certification) {
            return $certification->score >= $certification->event->min_score;
        })->count();

        $suspended = $finishedCertifications->filter(function ($certification) {
            return $certification->score < $certification->event->min_score;
        })->count();

        return json_encode([
            'approved' => $approved,
            'suspended' => $suspended,
        ]);

    }


    /**
     * Obtiene la cantidad de usuarios por estilo de aprendizaje
     * @return string|array
     */
    public function getQtyProfileUser($isSupervisor = false)
    {

        $query = User::select('profile_user');

        if (!$isSupervisor) {
            $idCompany = Auth::user()->company_id;
            $query->where('company_id', $idCompany);
        }

        $users = $query->get();

        $qtyProfileUser = [];

        foreach ($this->PROFILES as $value) {

            $qty = $users->filter(function ($user) use ($value) {
                return $user->profile_user == $value;
            })->count();

            $qtyProfileUser[$value] = $qty;
        }

        return json_encode($qtyProfileUser);

    }


    private function getSatisfaction($idCourse = NULL, $isSupervisor)
    {

        $start = Carbon::now()->startOfMonth();

        return collect([]);

    }

    public function getFilterSatisfaction($idCourse = NULL, $isSupervisor = false)
    {
        $responses = $this->getSatisfaction($idCourse, $isSupervisor);

        $muyInsatisfecho = $responses->filter(function ($response) {
            foreach ($response->surveyAnswers as $surveyAnswer) {
                if (in_array($surveyAnswer->pivot->answer, ['1. Muy Insatisfecho', 'Muy insatisfecho'])) {
                    return true;
                }
            }
            return false;
        })->count();

        $insatisfecho = $responses->filter(function ($response) {
            foreach ($response->surveyAnswers as $surveyAnswer) {
                if (in_array($surveyAnswer->pivot->answer, ['2. Insatisfecho'])) {
                    return true;
                }
            }
            return false;
        })->count();

        $niSatisfechoNiInsatisfecho = $responses->filter(function ($response) {
            foreach ($response->surveyAnswers as $surveyAnswer) {
                if (in_array($surveyAnswer->pivot->answer, ['3. Ni Satisfecho / Ni Insatisfecho', 'Ni Satisfecho / Ni Insatisfecho'])) {
                    return true;
                }
            }
            return false;
        })->count();

        $satisfecho = $responses->filter(function ($response) {
            foreach ($response->surveyAnswers as $surveyAnswer) {
                if (in_array($surveyAnswer->pivot->answer, ['4. Satisfecho'])) {
                    return true;
                }
            }
            return false;
        })->count();

        return json_encode([
            'muyInsatisfecho' => $muyInsatisfecho,
            'insatisfecho' => $insatisfecho,
            'niSatisfechoNiInsatisfecho' => $niSatisfechoNiInsatisfecho,
            'satisfecho' => $satisfecho,
        ]);

    }




    // ------------------ KPI THAT SUPERVISOR CAN SEE ------------------ //

    private function getFinishedCertificationSupervisor($idCourse = NULL)
    {
        $start = Carbon::now()->startOfMonth();

        return collect([]);

    }


    public function getStatusEvaluationsSupervisor($idCourse = NULL)
    {

        $finishedCertifications = $this->getFinishedCertificationSupervisor($idCourse);

        $approved = $finishedCertifications->filter(function ($certification) {
            return $certification->score >= $certification->event->min_score;
        })->count();

        $suspended = $finishedCertifications->filter(function ($certification) {
            return $certification->score < $certification->event->min_score;
        })->count();

        return json_encode([
            'approved' => $approved,
            'suspended' => $suspended,
        ]);

    }




}

