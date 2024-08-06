<?php

namespace App\Services;

use App\Models\Certification;
use App\Models\ForgettingCurve;
use Yajra\DataTables\Facades\DataTables;
use Str;

class ReportForgettingCurveService
{

    public function getDataTable()
    {

        $courses = ForgettingCurve::where('active', 'S')->with('courses')->get()->pluck('courses.*.id')->flatten()->toArray();

        $query = Certification::where([
            'status' => 'finished',
            'evaluation_type' => 'certification',
        ])
            ->whereYear('end_time', getCurrentYear())
            ->whereHas('event.exam.course', function ($query) use ($courses) {
                $query->where('course_type', 'REGULAR')->whereIn('id', $courses);
            })
            ->whereHas('event', function ($query) {
                $query->whereColumn('certifications.score', '>=', 'events.min_score')
                    ->whereDoesntHave('specCourse');
            })
            ->with([
                'user' => function ($q) {
                    $q->select('id', 'name', 'paternal', 'maternal', 'email');
                },
                'course' => function ($q2) {
                    $q2->with([
                        'forgettingCurves' => function ($q3) {
                            $q3->with([
                                'instances' => function ($q4) {
                                    $q4->with([
                                        'steps'
                                    ]);
                                }
                            ]);
                        },
                    ]);
                },
            ])
            ->get();

        $certifications = $query->groupBy(function ($item, $key) {
            return $item['user_id'] . '-' . $item['event']['exam']['course']['id'];
        })->map->first();

        return DataTables::of($certifications)
            ->addColumn('title_curve', function ($certification) {
                $forgettingCurve = $certification->course->forgettingCurves->first();
                return '<a href="' . route('admin.forgettingCurve.show', $forgettingCurve) . '">' . $forgettingCurve->title . '</a>';;
            })
            ->addColumn('min_score', function ($certification) {
                return $certification->course->forgettingCurves->first()->min_score;
            })
            ->editColumn('user.name', function ($certification) {
                return $certification->user->full_name_complete;
            })
            ->addColumn('steps_completed_septime', function ($certification) {
                $forgettingCurve = $certification->course->forgettingCurves->first();
                return getStepsCompleted($certification, getFcInstance($forgettingCurve, 7));
            })
            ->addColumn('end_date_first', function ($certification) {
                $forgettingCurve = $certification->course->forgettingCurves->first();
                return getFcStepProgressEndDate($forgettingCurve, $certification, 7);
            })
            ->addColumn('score_first', function ($certification) {
                $forgettingCurve = $certification->course->forgettingCurves->first();
                return getFcInstanceStepProgressScore($forgettingCurve, $certification, 7);
            })
            ->addColumn('steps_completed_fifteenth', function ($certification) {
                $forgettingCurve = $certification->course->forgettingCurves->first();
                return getStepsCompleted($certification, getFcInstance($forgettingCurve, 15));
            })
            ->addColumn('end_date_second', function ($certification) {
                $forgettingCurve = $certification->course->forgettingCurves->first();
                return getFcStepProgressEndDate($forgettingCurve, $certification, 15);
            })
            ->addColumn('score_second', function ($certification) {
                $forgettingCurve = $certification->course->forgettingCurves->first();
                return getFcInstanceStepProgressScore($forgettingCurve, $certification, 15);
            })
            ->addColumn('qualification', function ($certification) {

                $minScore = $certification->course->forgettingCurves->first()->min_score;
                $instances = $certification->course->forgettingCurves->first()->instances;

                return addQualificationForgettingCurve($certification, $minScore, $instances);

            })
            ->rawColumns(['title_curve', 'steps_completed_septimo', 'steps_completed_decimoquinto', 'qualification'])
            ->make(true);
    }



    public function getForgettingCurves()
    {

        $courses = ForgettingCurve::where('active', 'S')->with('courses')->get()->pluck('courses.*.id')->flatten()->toArray();

        $query = Certification::where([
            'status' => 'finished',
            'evaluation_type' => 'certification',
        ])
            ->whereYear('end_time', getCurrentYear())
            ->whereHas('event.exam.course', function ($query) use ($courses) {
                $query->where('course_type', 'REGULAR')->whereIn('id', $courses);
            })
            ->whereHas('event', function ($query) {
                $query->whereColumn('certifications.score', '>=', 'events.min_score')
                    ->whereDoesntHave('specCourse');
            })
            ->with([
                'user' => function ($q) {
                    $q->select('id', 'name', 'paternal', 'maternal', 'email');
                },
                'course' => function ($q2) {
                    $q2->with([
                        'forgettingCurves' => function ($q3) {
                            $q3->with([
                                'instances' => function ($q4) {
                                    $q4->with([
                                        'steps'
                                    ]);
                                }
                            ]);
                        },
                    ]);
                },
            ])
            ->get();


        return $query->groupBy(function ($item, $key) {
            return $item['user_id'] . '-' . $item['event']['exam']['course']['id'];
        })->map->first();


    }


}



