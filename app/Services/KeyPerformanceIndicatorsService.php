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

        $query = Certification::with([
            'event:id,date,min_score',
        ])
            ->where('company_id', $idCompany)
            ->where('evaluation_type', 'certification')
            ->where('status', 'finished')
            ->whereHas('event', function ($query) use ($start) {
                $query->select('id', 'date', 'min_score')->whereBetween('date', [$start, getCurrentDate()]);
            });

        if ($idCourse) {
            $query->whereHas('event.exam.course', function ($query) use ($idCourse) {
                $query->select('id')->where('id', $idCourse);
            });
        }

        return $query->get(['id', 'event_id', 'score']);

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

        $query = UserSurvey::with([
            'surveyAnswers' => function ($query) {
                $query->where('statement_id', 44);
            },
            'event.course',
        ])->where('status', 'finished');


        if (!$isSupervisor) {
            $idCompany = Auth::user()->company_id;
            $query->where('company_id', $idCompany);
        }

        $query->whereBetween('date', [$start, getCurrentDate()])
            ->whereHas('surveyAnswers', function ($query) {
                $query->where('statement_id', 44);
            });


        if ($idCourse) {
            $query->whereHas('event.course', function ($query) use ($idCourse) {
                $query->where('courses.id', $idCourse);
            });
        }


        return $query->get();

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
        $query = Certification::with([
            'event:id,date,min_score',
        ])
            ->where('evaluation_type', 'certification')
            ->where('status', 'finished')
            ->whereHas('event', function ($query) use ($start) {
                $query->select('id', 'date', 'min_score')
                    ->whereBetween('date', [$start, getCurrentDate()]);
            });

        if ($idCourse) {
            $query->whereHas('event.exam.course', function ($query) use ($idCourse) {
                $query->select('id')->where('id', $idCourse);
            });
        }

        return $query->get(['id', 'event_id', 'score']);

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

