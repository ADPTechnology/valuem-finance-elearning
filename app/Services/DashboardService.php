<?php

namespace App\Services;

use App\Models\{Certification, Course, User};
use Carbon\Carbon;
use DB;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class DashboardService
{

    private function getFinishedCertifications()
    {

        $start = Carbon::now()->startOfMonth();

        return collect([]);
    }

    public function getStatusEvaluations()
    {

        $finishedCertifications = $this->getFinishedCertifications();

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
     * Obtiene la cantidad de usuarios por tipo de rol
     * @return string|array
     */
    public function getTypeRole()
    {

        $roles = config('parameters.roles');

        $roleCounts = [];

        $users = User::select('role')->get();

        foreach ($roles as $key => $value) {

            $qty = $users->filter(function ($user) use ($key) {
                return $user->role == $key;
            })->count();

            $roleCounts[$key] = $qty;
        }

        return json_encode($roleCounts);

    }




}
