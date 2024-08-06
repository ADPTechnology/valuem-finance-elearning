<?php

namespace App\Console\Commands;

use App\Mail\ForgettingNotificationCurve;
use App\Models\Certification;
use App\Models\FcStepProgress;
use App\Models\ForgettingCurve;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to users after 7 or 15 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Summary of sendEmailFunction
     * @param mixed $certification
     * @param mixed $instance
     * @param mixed $steps
     * @return void
     */
    public function sendEmailFunction(Certification $certification, $instance, $steps)
    {
        Mail::to($certification->user->email)->send(new ForgettingNotificationCurve($certification, $instance));

        foreach ($steps as $step) {
            FcStepProgress::create([
                'status' => 'pending',
                'certification_id' => $certification->id,
                'f_curve_step_id' => $step->id
            ]);
        }

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // 1. mestra respuestas
        // 2. video y preguntas -> guardar
        // 3. examen sin mostrar

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

        return 1;
    }
}

