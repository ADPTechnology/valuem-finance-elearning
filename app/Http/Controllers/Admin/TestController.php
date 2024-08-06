<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignable;
use App\Models\Assignment;
use App\Models\Certification;
use App\Models\Course;
use App\Models\Event;
use App\Models\FcStepProgress;
use App\Models\File;
use App\Models\ForgettingCurve;
use App\Models\GroupEvent;
use App\Models\ParticipantGroup;
use App\Models\SpecCourse;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use App\Mail\ForgettingNotificationCurve;


class TestController extends Controller
{

    public function TestAssignment()
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

        // dd($query);

        $certifications = $query->groupBy(function ($item, $key) {
            return $item['user_id'] . '-' . $item['event']['exam']['course']['id'];
        })->map->first();

        dd($certifications);


        foreach ($certifications as $certification) {

            $forgettingCurve = $certification->course->forgettingCurves->first();

            $instances = $forgettingCurve->instances;

            $completeInstanceBefore = true;


            foreach ($instances as $instance) {

                $steps = $instance->steps;

                $ifSendEmail = false;

                $endTime = Carbon::parse($certification->end_time);

                if ($instance->days_count == 7 && $endTime->diffInDays(Carbon::now()) >= 7) {

                    foreach ($steps as $step) {

                        $stepProgress = $step->fcStepProgress()->where('certification_id', $certification->id)->first();

                        if ($stepProgress == null) {

                            $ifSendEmail = true;
                            $completeInstanceBefore = false;

                        } elseif ($stepProgress) {

                            if ($stepProgress->status == 'pending' || $stepProgress->status == 'in_progress') {
                                $completeInstanceBefore = false;
                            }
                        }
                    }
                }

                if ($instance->days_count == 15 && $endTime->diffInDays(Carbon::now()) >= 15) {

                    foreach ($steps as $step) {

                        $stepProgress = $step->fcStepProgress()->where('certification_id', $certification->id)->first();

                        if ($stepProgress == null) {

                            $ifSendEmail = true;

                        }
                    }
                }


                if ($ifSendEmail && $instance->days_count == 7) {

                    $this->sendEmailFunction($certification, $instance, $steps);

                }


                if ($ifSendEmail && $completeInstanceBefore && $instance->days_count == 15) {

                    $this->sendEmailFunction($certification, $instance, $steps);

                }

            }


        }



        return view('welcome');
    }

    // $this->sendEmailFunction($certification, $instance, $steps);


}
