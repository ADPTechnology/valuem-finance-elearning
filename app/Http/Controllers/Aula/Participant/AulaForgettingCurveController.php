<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\ForgettingCurve;
use Auth;
use Illuminate\Http\Request;

class AulaForgettingCurveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $certifications = $user->certifications()->where([
            'status' => 'finished',
            'evaluation_type' => 'certification'
        ])
            ->whereHas('fcStepProgress')
            ->whereHas('course.forgettingCurves', function ($q3) {
                $q3->where('active', 'S');
            })
            ->with([
                'course' => function ($q2) {
                    $q2->with([
                        'forgettingCurves' => function ($q3) {
                            $q3->with([
                                'file' => function ($q4) {
                                    $q4->where('file_type', 'imagenes');
                                },
                                'instances' => function ($q4) {
                                    $q4->with([
                                        'steps' => function ($q5) {
                                            $q5->with('fcStepProgress');
                                        }
                                    ]);
                                }
                            ]);

                        },
                    ]);

                },
            ])
            ->get();

        return view('aula.viewParticipant.forgettingCurve.index', compact('certifications'));
    }


    public function show(ForgettingCurve $forgettingCurve, Certification $certification)
    {

        $forgettingCurve->load([
            'instances' => function ($q1) {
                $q1->with([
                    'steps'
                ]);
            }
        ]);

        return view('aula.viewParticipant.forgettingCurve.show', compact('forgettingCurve', 'certification'));

    }

}
