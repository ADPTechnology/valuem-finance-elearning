<?php

use Carbon\Carbon;
use App\Models\{
    DynamicAlternative,
    Certification,
    Event,
    DroppableOption,
    CourseSection,
    File as ModelsFile,
    SectionChapter,
    Survey,
    UserSurvey,
    SurveyStatement,
    SurveyOption,
    User,
    Config,
    Course,
    SpecCourse,
    Webinar
};

Carbon::setLocale('es');

date_default_timezone_set("America/Lima");

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Str;

function setActive($routeName)
{
    return request()->routeIs($routeName) ? 'active' : '';
}

// ---------- SIDEBAR ----------------

function hasElearning()
{
    $user = Auth::user();

    return Course::whereHas('exams.events.certifications', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                    ->where('course_type', 'REGULAR');
                })->where('active', 'S')
                ->count() > 0 ? true : false;

}

function hasFreeCourses()
{
    $user = Auth::user();

    return Course::where('course_type', 'FREE')
                    ->withCount('courseChapters')
                    ->whereHas('productCertifications', function ($query) use ($user) {
                        $query->where([
                            'user_id' => $user->id,
                            'status' => 'approved'
                        ]);
                    })
                    ->where('active', 'S')
                    ->having('course_chapters_count', '>', 0)
                    ->count() > 0 ? true : false;
}

function hasWebinar()
{
    $user = Auth::user();

    $query = Webinar::where([
                'active' => 'S'
            ]);

    if ($user->role == 'instructor') {
        $query->whereHas('events', function ($q) use ($user) {
                $q->where([
                    'active' => 'S',
                    'user_id' => $user->id
                ]);
            });
    }
    else if ($user->role == 'participants') {
        $query->whereHas('events', function ($q) use ($user) {
                $q->where('active', 'S')
                    ->whereHas('certifications', function ($q1) use ($user) {
                        $q1->where('user_id', $user->id);
                    });
            });
    }

    return $query->count() > 0 ? true : false;
}

function hasSpecCourses()
{
    $user = Auth::user();

    $query = SpecCourse::where('active', 'S');

    if ($user->role == 'instructor') {
        $query = $query->whereHas('groupEvents.events', function ($q) use ($user) {
            $q->where('events.user_id', $user->id);
        });
    }
    else if ($user->role == 'participants') {
        $query = $query->whereHas('specCourseCertifications', function ($q) use ($user) {
                    $q->where('certifications.user_id', $user->id);
                });
    }

    return $query->count() > 0 ? true : false;
}

function hasLiveFreeCourses()
{
    $user = Auth::user();

    $query = Course::where([
            'active' => 'S',
            'course_type' => 'LIVEFREECOURSE'
        ])
        ->has('events')
        ->whereHas('events', function ($q) {
            $q->doesntHave('courseModule');
        });

    if ($user->role == 'instructor') {
        $query = $query->whereHas('events', function ($q) use ($user) {
            $q->where('events.user_id', $user->id);
        });
    }
    else if ($user->role == 'participants') {
        $query->whereHas('events.certifications', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    return $query->count() > 0 ? true : false;
}

function hasForgettingCurve()
{
    $user = Auth::user();

    return $user->certifications()->where([
            'status' => 'finished',
            'evaluation_type' => 'certification'
        ])
        ->whereHas('fcStepProgress')
        ->whereHas('course.forgettingCurves', function ($q3) {
            $q3->where('active', 'S');
        })
        ->count() > 0 ? true : false;
}







function getMessageFromSuccess($success, $context)
{
    $message = $success ? config('parameters.' . $context . '_message') : config('parameters.exception_message');

    return $message;
}

function getSelectedAnswers($certification)
{
    $num_question = $certification->evaluations()
        ->where('selected_alternatives', '!=', null)
        ->count();

    return $num_question;
}

function getSelectedAnswersStepProgress($fcStepProgress)
{
    $num_question = $fcStepProgress->evaluations
        ->where('selected_alternatives', '!=', null)
        ->count();

    return $num_question;
}

function getSelectedAnswersUserFcEvaluation($userFcEvaluation)
{
    $num_question = $userFcEvaluation->evaluations
        ->where('selected_alternatives', '!=', null)
        ->count();

    return $num_question;
}

function getCountQuestions($step)
{
    $num_question = $step->exams()
        ->where('active', 'S')
        ->first()
        ->questions()
        ->where('active', 'S')
        ->count();

    return $num_question;
}

function getExamFromCertification($certification)
{
    return $certification->event->exam;
}

function getCourseFromEvent(Event $event)
{
    return $event->exam->course;
}

function getQuestionsFromExam($exam)
{
    $questions = $exam->questions()->inRandomOrder()->get();

    return $questions;
}

function getCorrectAltFromQuestion($question)
{
    $correct_alternatives = $question->alternatives()
        ->where('is_correct', 1)
        ->get();

    return $correct_alternatives;
}

function getScoreFromCertification($certification)
{
    $certification->load([
        'evaluations' => function ($q) use ($certification) {
            $q->where('evaluation_time', $certification->evaluation_time)
                ->with('question');
        }
    ]);

    $event = $certification->event;
    $exam = $event->exam;
    $points = $certification->evaluations->sum('points');
    $avg = $exam->questions_avg_points;

    $max_score = round($avg * $certification->evaluations->count());

    $correct_answers = 0;

    foreach ($certification->evaluations as $evaluation) {
        if ($evaluation->points == $evaluation->question->points) {
            $correct_answers++;
        }
    }

    $score = ($points > $max_score ||
        $correct_answers == $certification->evaluations->count()) ?
        $max_score : $points;

    return $score;
}


function getScoreFromStep($fcStepProgress)
{
    $fcStepProgress->load([
        'evaluations' => function ($q) {
            $q->with('question');
        },
        'step.exams' => function ($q) {
            $q->withAvg('questions', 'points');
        }
    ]);

    // $event = $certification->event;
    $exam = $fcStepProgress->step->exams->first();
    $points = $fcStepProgress->evaluations->sum('points');
    $avg = $exam->questions_avg_points;

    $max_score = round($avg * $fcStepProgress->evaluations->count());

    $correct_answers = 0;

    foreach ($fcStepProgress->evaluations as $evaluation) {
        if ($evaluation->points == $evaluation->question->points) {
            $correct_answers++;
        }
    }

    $score = ($points > $max_score ||
        $correct_answers == $fcStepProgress->evaluations->count()) ?
        $max_score : $points;

    return $score;
}

function getScoreFromUserFcEvaluation($userFcEvaluation)
{
    $userFcEvaluation->load([
        'evaluations' => function ($q) {
            $q->with('question');
        },
        'fcEvaluation.exam' => function ($q) {
            $q->withAvg('questions', 'points');
        }
    ]);

    $exam = $userFcEvaluation->fcEvaluation->exam;
    $points = $userFcEvaluation->evaluations->sum('points');
    $avg = $exam->questions_avg_points;

    $max_score = round($avg * $userFcEvaluation->evaluations->count());

    $correct_answers = 0;

    foreach ($userFcEvaluation->evaluations as $evaluation) {
        if ($evaluation->points == $evaluation->question->points) {
            $correct_answers++;
        }
    }

    $score = ($points > $max_score ||
        $correct_answers == $userFcEvaluation->evaluations->count()) ?
        $max_score : $points;

    return $score;
}


function getScoreFromStepVideo($fcStepProgress)
{
    $fcStepProgress->load([
        'evaluations' => function ($q) {
            $q->with('question');
        },
        'step.video' => function ($q) {
            $q->withAvg('questions', 'points');
        }
    ]);

    $video = $fcStepProgress->step->video;
    $points = $fcStepProgress->evaluations->sum('points');
    $avg = $video->questions_avg_points;

    $max_score = round($avg * $fcStepProgress->evaluations->count());

    $correct_answers = 0;

    foreach ($fcStepProgress->evaluations as $evaluation) {
        if ($evaluation->points == $evaluation->question->points) {
            $correct_answers++;
        }
    }

    $score = ($points > $max_score ||
        $correct_answers == $fcStepProgress->evaluations->count()) ?
        $max_score : $points;

    return $score;
}

function getFinishedChaptersCountBySection($section)
{
    return $section->sectionChapters->sum(function ($chapter) {
        return $chapter->progressUsers->sum(function ($progress) {
            return $progress->pivot->status == 'F' ? 1 : 0;
        });
    });
}



function getTimeDifference($evalOrCertif, $exam)
{
    $evaluation_time = $evalOrCertif->evaluation_time;

    $diff_time = ($evaluation_time + ($exam->exam_time * 60)) - time();

    return $diff_time;
}

function getItsTimeOut($diff_time)
{
    return $diff_time + 1 < 0 ? true : false;
}

function getFcInstance($forgettingCurve, int $daysCount)
{
    return $forgettingCurve->instances->filter(function ($instance) use ($daysCount) {
        return $instance->days_count == $daysCount;
    })->first();
}

function getFcStepProgressCollection($certification, $instance)
{
    return $instance ? $instance->steps->map(function ($step) use ($certification) {
        return $step->fcStepProgress()->with('step')->where('certification_id', $certification->id)->first() ?? NULL;
    })->flatten()->filter(function ($step) {
        return $step != NULL;
    }) : collect();
}

function getFcStepProgressEndDate($forgettingCurve, $certification, int $daysCount)
{
    $instance = getFcInstance($forgettingCurve, $daysCount);
    $stepProgressCollection = getFcStepProgressCollection($certification, $instance);
    $step = $stepProgressCollection->every(function ($stepProgress, $key) {
        return $stepProgress->status == 'finished';
    }) && $stepProgressCollection->isNotEmpty() ?
        $stepProgressCollection
        ->sortByDesc('updated_at')
        ->first()
        : NULL;

    return $step->updated_at ?? '-';
}

function getFcInstanceStepProgressScore($forgettingCurve, $certification, $daysCount)
{
    $instance = getFcInstance($forgettingCurve, $daysCount);
    $stepProgressCollection = getFcStepProgressCollection($certification, $instance);

    $qttyEvs = $instance->steps->sum(function ($step) {
        return in_array($step->type, ['video', 'evaluation']) ? 1 : 0;
    });

    return $stepProgressCollection->sum(function ($stepProgress) use ($qttyEvs) {
        return $stepProgress->status == 'finished' &&
            in_array($stepProgress->step->type, ['video', 'evaluation']) ?
            ($stepProgress->score / $qttyEvs) : 0;
    }) ?? '-';
}


function addQualificationForgettingCurve($certification, $minScore, $instances)
{
    $score = 0;
    $notCompleted = false;
    $qtyEvs = 0;

    foreach ($instances as $instance) {

        $steps = $instance->steps;

        foreach ($steps as $step) {

            $stepProgress = $step->fcStepProgress()->where('certification_id', $certification->id)->first();

            if ($stepProgress == null) {
                $notCompleted = true;
                break;
            } else if ($step->type == 'video' || $step->type == 'evaluation') {

                if ($stepProgress->status == 'finished') {
                    $score += $stepProgress->score;
                    $qtyEvs++;
                } else {
                    $notCompleted = true;
                }
            }
        }
    }

    return $notCompleted ? '-' : (($score / $qtyEvs) >= $minScore ? 'Aprobado' : 'Desaprobado');
}

function getStepsCompleted($certification, $instance)
{
    $notCompleted = false;
    $qtySteps = 0;
    $qtyCompleted = 0;

    foreach ($instance->steps as $step) {

        $qtySteps++;

        $stepProgress = $step->fcStepProgress()->where('certification_id', $certification->id)->first();

        if ($stepProgress == null) {
            $notCompleted = true;
        } else if ($stepProgress !== null && $stepProgress->status == 'finished') {
            $qtyCompleted++;
        }
    }

    return $notCompleted ? '-' : ($qtyCompleted . ' / ' . $qtySteps);
}


// function getCertificationsFromCourse(Course $course)
// {
//     $user = Auth::user();

//     $certifications = $user->certifications()
//         ->select(
//             'id',
//             'user_id',
//             'event_id',
//             'evaluation_type',
//             'status',
//             'score',
//             'assist_user',
//             'evaluation_time'
//         )
//         ->with('evaluations:id,certification_id,points')
//         ->whereHas('event', function ($q) {
//             $q->doesntHave('courseModule');
//         })
//         ->with([
//             'event' => fn ($query) => $query
//                 ->select('id', 'exam_id', 'type', 'date', 'description', 'user_id', 'min_score', 'questions_qty')
//                 ->with('user:id,name,paternal,maternal')
//                 ->with([
//                     'exam' => fn ($query2) => $query2
//                         ->select('id', 'course_id', 'owner_company_id', 'exam_time')
//                         ->with('ownerCompany:id,name')
//                         ->withCount('questions')
//                         ->withAvg('questions', 'points')
//                 ])
//                 ->doesntHave('courseModule')
//         ])
//         ->get()
//         ->filter(function ($certification) use ($course) {
//             if ($certification->event->exam->course_id == $course->id && $certification->evaluation_type == 'certification')
//                 return $certification;
//         })->sortByDesc('id');

//     return $certifications;
// }

function getDroppableOptionsFromQuestion($question)
{
    $droppable_options = DroppableOption::join('dynamic_alternatives', 'dynamic_alternatives.id', '=', 'droppable_options.dynamic_alternative_id')
        ->join('dynamic_questions', 'dynamic_questions.id', '=', 'dynamic_alternatives.dynamic_question_id')
        ->where('dynamic_questions.id', $question->id)
        ->get('droppable_options.*');

    return $droppable_options;
}

function getAlternativeFromId($id)
{
    return DynamicAlternative::with('file')->findOrFail($id);
}

function getDroppableOptionFromId($id)
{
    return DroppableOption::findOrFail($id);
}

function updateIfNotFinished($certification): void
{
    $current_date_string = Carbon::now('America/Lima')->format('Y-m-d');
    $current_date = Carbon::parse($current_date_string);
    $event_date = Carbon::parse(($certification->event->date));

    // if ($current_date->greaterThan($event_date) && $certification->status != 'finished') {
    //     $exam = getExamFromCertification($certification);
    //     $total_time = $exam->exam_time;
    //     $score = getScoreFromCertification($certification);

    //     $certification->update([
    //         'status' => 'finished',
    //         'end_time' => Carbon::now('America/Lima'),
    //         'total_time' => $total_time,
    //         'score' => $score,
    //     ]);
    // }
    if ($certification->status == 'in_progress') {
        $exam = getExamFromCertification($certification);
        $diff_time = getTimeDifference($certification, $exam);
        $its_time_out = getItsTimeOut($diff_time);

        if ($its_time_out) {
            $total_time = $exam->exam_time;
            $score = getScoreFromCertification($certification);

            $certification->update([
                'status' => 'finished',
                'end_time' => Carbon::now('America/Lima'),
                'total_time' => $total_time,
                'score' => $score,
            ]);
        }
    }
}


/*-------------- E LEARNING ----------------*/

function getInstructorsBasedOnUserAndCourse($course)
{
    $user = Auth::user();
    $role = $user->role;
    $instructors = collect();

    if ($role == 'instructor') {
        $instructors = collect();
        $instructors = $instructors->push($user);
    } else if ($role == 'participants') {
        $instructors = $course->exams->map(function ($exam) {
            return $exam->events->map(function ($event) {
                return $event->user;
            });
        })->collapse()->unique();
    } else if (in_array($role, ['security_manager', 'security_manager_admin'])) {
        $instructors = $course->events->map(function ($event) {
            return $event->user;
        })
            ->unique();
    }

    return $instructors;
}

function getNStudentsFromCourse($course)
{
    $role = Auth::user()->role;

    if ($role == 'participants') {
        $nstudents = $course->exams->map(function ($exam) {
            return $exam->events->map(function ($event) {
                return $event->certifications->pluck('user_id');
            });
        })->flatten(2)->unique()->count();
    } elseif (in_array($role, ['instructor', 'security_manager', 'security_manager_admin'])) {
        $nstudents = $course->users_course_count;
    }

    return $nstudents;
}

function getProgressCertificationsFromCourse($course)
{
    $user = Auth::user();

    $certifications = $course->exams->map(function ($exam) {
        return $exam->events->map(function ($event) {
            return $event->certifications;
        });
    })->flatten(2)->where('user_id', $user->id);

    return $certifications;
}

function getProgressCertificationsFromFclCourse($course)
{
    $user = Auth::user();

    $certifications = $course->events->map(function ($event) {
        return $event->certifications;
    })->flatten()->where('user_id', $user->id);

    return $certifications;
}

function getEventFromCourseAndCertification($course, $certification)
{
    $event = $course->exams->map(function ($exam) {
        return $exam->events;
    })->flatten()->where('id', $certification->event_id)->first();

    return $event;
}

function getApprSuspFclCertifications($certifications, $flag)
{
    $filteredCert = $certifications->where('status', 'finished')->filter(function ($certification, $key) use ($flag) {
        $score = $certification->score;
        if ($score_t = $certification->score_fin) {
            $score = ($score * 0.4) + ($score_t * 0.6);
        };

        return $flag == 'approved' ?
            $score >= $certification->event->min_score :
            $score < $certification->event->min_score;
    });

    return $filteredCert;
}


function getOwnerCompanyFromCertification(Certification $certification)
{
    return $certification->event->exam->ownerCompany;
}

// ------------------------------------------------


// ------------ SPEC COURSES -----------------

function getSpecCourseInstructors($specCourse)
{
    $instructors = $specCourse->groupEvents->flatMap->events->map(function ($event) {
        return $event->user;
    })->unique();


    return $instructors ?? collect();
}

function getCourseFreeLiveInstructors($course)
{
    $instructors = $course->events->map(function ($event) {
        return $event->user;
    })->unique();

    return $instructors ?? collect();
}

function getWebinarInstructors($webinar)
{
    $instructors = $webinar->events->map(function ($event) {
        return $event->instructor;
    })->unique();

    return $instructors ?? collect();
}


// ---------------- FREE COURSES ------------------


function getFreeCourseTime($duration)
{
    $hours = intdiv($duration, 60);
    $minuts = $duration % 60;

    return $hours . ' hrs ' . $minuts . ' minutos';
}

function getCompletedChapters($chapters)
{
    $completedChapters = $chapters->sum(function ($chapter) {
        if ($chapter->progressUsers->first()) {
            return $chapter->progressUsers->first()->pivot->status == 'F' ? 1 : 0;
        }
    });

    return $completedChapters;
}

function getCompletedEvaluations($evaluations)
{
    return $evaluations->sum(function ($evaluation) {
        return $evaluation->userEvaluations->sum(function ($userEval) {
            return $userEval->pivot->status == 'finished' ? 1 : 0;
        });
    });
}

function getShowSection(CourseSection $current_section, CourseSection $section)
{
    return $current_section->id == $section->id ? 'show' : '';
}

function getItsChapterFinished(SectionChapter $chapter, $allProgress)
{
    $progress = $allProgress->where('id', $chapter->id)->first();
    if ($progress) {
        return $progress->pivot->status == 'F' ? true : false;
    }
}

function getNFinishedChapters($section, $allProgress)
{
    $Nchapters = $allProgress->where('section_id', $section->id)
        ->sum(function ($chapter) {
            return $chapter->pivot->status == 'F' ? 1 : 0;
        });

    return $Nchapters;
}


// ----------------- PROGRESS -----------------

function getSpecCourseEvaluationScore($specCourse)
{
    $score_total = $specCourse->events->sum(function ($event) {
        return $event->certifications->sum(function ($certification) {
            return $certification->score;
        });
    });

    if ($specCourse->total_events_count != 0) {
        $score = round($score_total / $specCourse->total_events_count, 1);
    }

    return $score ?? 0;
}

function getSpecCourseAssignmentsScore($specCourse)
{
    $score_events = $specCourse->events->sum(function ($event) {
        return $event->assignments->sum(function ($assignment) {

            $net_sum_score = $assignment->certifications->sum(function ($certification) {
                return $certification->pivot->points;
            });

            $net_sum_score += $assignment->participantGroups->sum(function ($participantGroup) {
                return $participantGroup->pivot->points;
            });

            return $net_sum_score * (($assignment->value ?? 0) / 100);
        });
    });

    // if ($specCourse->events_with_assignments_count != 0) {
    //     $score = round(($score_events / $specCourse->events_with_assignments_count), 1);
    // }

    return $score_events ?? 0;
}



// --------------- SURVEYS ------------------

function validateSurveys()
{
    $user = Auth::user();

    // if ($user->user_type === 'external')
    //     return false;

    $surveys = $user->userSurveys()->whereHas('survey', function ($query) {
        $query->where('active', 'S');
    })
        ->with('survey:id,destined_to')
        ->get();

    $checkEmptyUserProfile = $surveys->filter(function ($survey) {
        return $survey->survey->destined_to == 'user_profile';
    })->isEmpty();

    if ($checkEmptyUserProfile && $user->profile_survey == 'N') {
        storeUserSurvey('user_profile', $user, NULL);
    }

    $checkPendingSurveys = $surveys->filter(function ($survey) {
        return $survey->status == 'pending';
    })->isNotEmpty();

    return $checkPendingSurveys;
}

function validateForgettingCurve()
{
    $user = Auth::user();

    $certifications = $user->certifications()
        ->where([
            'evaluation_type' => 'certification',
            'status' => 'finished'
        ])
        ->with('fcStepProgress:id,status,certification_id')
        ->get();

    $checkIfExistsProgress = $certifications->filter(function ($certification) {
        return $certification->fcStepProgress->filter(function ($fcStepProgress) {
            return $fcStepProgress->status == 'pending';
        })->isNotEmpty();
    })->isNotEmpty();

    return $checkIfExistsProgress;
}

function storeUserSurvey($destined_to, User $user, $event_id)
{
    $survey = Survey::where('destined_to', $destined_to)
        ->where('active', 'S')
        ->first();

    if ($survey != null) {
        return UserSurvey::create([
            'user_id' => $user->id,
            'survey_id' => $survey->id,
            "company_id" => $user->company_id,
            'date' => getCurrentDate(),
            'status' => 'pending',
            'start_time' => null,
            'end_time' => null,
            'total_time' => null,
            'event_id' => $event_id
        ]);
    }

    return false;
}

function getOptionsFromStatement(SurveyStatement $statement)
{
    $options = $statement->options->sortByDesc('description');

    return $options;
}

function getOptionsFromStatementAsc(SurveyStatement $statement)
{
    $options = $statement->options->sortBy('description');

    return $options;
}

function getChekedInput(SurveyStatement $statement, SurveyOption $option)
{
    return $statement->pivot->answer == $option->description ? 'checked' : '';
}

function verifyLastSurveyGroup($answersByGroup, $group_position)
{
    return $answersByGroup->count() == $group_position ? true : false;
}

function getProfileTypes(UserSurvey $userSurvey)
{
    $EC = 0;
    $OR = 0;
    $CA = 0;
    $EA = 0;

    foreach ($userSurvey->surveyAnswers as $surveyAnswer) {
        if (Str::contains($surveyAnswer->pivot->answer, '(EC)') == '(EC)')
            $EC++;
        if (Str::contains($surveyAnswer->pivot->answer, '(OR)') == '(OR)')
            $OR++;
        if (Str::contains($surveyAnswer->pivot->answer, '(CA)') == '(CA)')
            $CA++;
        if (Str::contains($surveyAnswer->pivot->answer, '(EA)') == '(EA)')
            $EA++;
    }

    return array(
        "EC" => $EC,
        "OR" => $OR,
        "CA" => $CA,
        "EA" => $EA
    );
}



// ---------------------------------------------




function getStatusClass($status)
{
    return ($status === 'S' ||
        $status === 1) ? 'active' : '';
}

function getStatusText($status)
{
    return ($status === 'S' ||
        $status === 1) ? 'Activo' : 'Inactivo';
}

function getStatusButton($status)
{
    return '<span class="status ' . ($status == 'S' ? 'active' : 'inactive') . '">' .
        ($status == 'S' ? 'Activo' : 'Inactivo') .
        '</span>';
}

function getStatusRecomended($status)
{
    return $status == 1
        ? '<i class="fa-solid fa-star flg-recom-btn active"></i>'
        : '<i class="fa-regular fa-star flg-recom-btn"></i>';
}

function getSelectedOption(CourseSection $section, $order)
{
    return $section->section_order == $order ? 'selected' : '';
}

function getGlobalSelectedOption($model, $order)
{
    return $model->order == $order ? 'selected' : '';
}

function setSectionActive($model, $active)
{
    return $model->id == $active ? 'active' : '';
}

function getActiveSection($active)
{
    return $active != null ? $active : '';
}

function getAssignmentType($flg)
{
    return $flg == 1 ? 'Grupal' : 'Individual';
}

function getAssignmentStatus($flg)
{
    return $flg == 1 ? 'Habilitado' : 'Inhabilitado';
}

function getEventParticipantsIds(Event $event)
{
    $user_ids = $event->certifications()
        ->where('evaluation_type', 'certification')
        ->get()
        ->pluck('user_id')
        ->unique()
        ->toArray();

    return $user_ids;
}



// ---------- FILE -------------

function verifyUserAvatar($file)
{
    $url = asset('storage/img/user_avatar/default.png');

    if ($file) {
        $url = $file->file_url ?? $url;
    }

    return $url;
}

function verifyImage($file)
{
    $url = asset('storage/img/common/no-image.png');
    if ($file) {
        $url = $file->file_url ?? $url;

        // TARDA DEMASIADO CUANDO SON VARIAS IMÁGENES

        // $directory = (explode('/', str_ireplace(array('http://', 'https://'), '', $url)))[0];

        // if ($directory == 'localhost' || $directory == '127.0.0.1:8000') {
        //     $url = $url == null ? asset('storage/img/common/no-image.png')
        //         : $url;
        // } else {
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);

        //     curl_setopt($ch, CURLOPT_NOBODY, 1);
        //     curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //     $result = curl_exec($ch);
        //     curl_close($ch);
        //     if ($result == false) {
        //         $url = asset('storage/img/common/no-image.png');
        //     }
        // }
    }

    return $url;
}

function verifyFile($file)
{
    return $file != null ? $file->file_url : null;
}

function verifyUrl($url)
{
    $return_url = asset('storage/img/common/no-image.png');
    if ($url != '') {
        $return_url = $url;
    }

    return $return_url;
}



/**
 * Summary of checkPastDateTime
 * @param mixed $date
 * @param mixed $time
 * @return bool
 */
function checkPastDateTime($date, $time)
{
    $current_date = getCurrentDate();
    $current_time = getCurrentTime();

    if ($date <= $current_date) {
        if ($time < $current_time) {
            return true;
        }
    }

    return false;
}

//--------------------------------





// ----------- DATE TIME CARBON ------------

function getCurrentDate()
{
    return Carbon::now('America/Lima')->format('Y-m-d');
}

function getCurrentTime()
{
    return Carbon::now('America/Lima')->format('H:i:s');
}

function getDiffForHumansFromTimestamp($timestamp)
{
    return Carbon::parse($timestamp)->diffForHumans();
}

function getTimeforHummans($time)
{
    return Carbon::parse($time)->format('g:i A');
}
function getDateForHummans($date)
{
    return Carbon::parse($date)->locale('es')->isoFormat('dddd D [de] MMMM [del] YYYY');
}

function getCurrentYear()
{
    return Carbon::now('America/Lima')->format('Y');
}


function formatUpdateAt($update_at)
{
    return $update_at->format('Y-m-d');
}

function getCurrentMonth()
{
    return Carbon::now('America/Lima')->isoFormat('MMMM');
}

function getCarbonInstance($date)
{
    return Carbon::parse($date);
}

// ----------------------------------



function normalizeInputStatus($data)
{
    $data['active'] = isset($data['active']) ? 'S' : 'N';

    $data['flg_recom'] = isset($data['flg_recom']) ? 1 : 0;

    $data['status'] = isset($data['status']) ? 'S' : 'N';

    $data['flg_test_exam'] = isset($data['flg_test_exam']) ? 'S' : 'N';

    $data['flg_public'] = isset($data['flg_public']) ? 'S' : 'N';

    $data['flg_groupal'] = isset($data['flg_groupal']) ? 'S' : 'N';

    $data['flg_evaluable'] = isset($data['flg_evaluable']) ? 'S' : 'N';

    $data['flg_asist'] = isset($data['flg_asist']) ? 'S' : 'N';

    $data['flg_survey_course'] = isset($data['flg_survey_course']) ? 'S' : 'N';

    $data['flg_survey_evaluation'] = isset($data['flg_survey_evaluation']) ? 'S' : 'N';

    $data['assist_user'] = isset($data['assist_user']) ? 'S' : 'N';

    return $data;
}

function verifyEventType($type)
{
    switch ($type) {
        case 'present':
            $type = 'present';
            break;
        case 'P':
            $type = 'present';
            break;
        case 'virtual':
            $type = 'virtual';
            break;
        case 'V':
            $type = 'virtual';
            break;
        default:
            $type = '';
    }

    return $type;
}

function verifyCurveStepsType($type)
{
    return config('parameters.curve_steps_types')[$type];
}


// -------------- CERTIFICATIONS ----------------

function getMiningUnitSufix($description)
{
    $sufix = '-';

    if (Str::is('*ATACOCHA*', strtoupper($description)))
        $sufix = 'A';
    if (Str::is('*PORVENIR*', strtoupper($description)))
        $sufix = 'P';
    if (Str::is('*SINAYCOCHA*', strtoupper($description)))
        $sufix = 'S';
    if (Str::is('*CERRO*', strtoupper($description)))
        $sufix = 'C';

    return $sufix;
}





/* ----------- HOME PAGE ------------*/


function getInstructorsFromCourseHome($course)
{
    $instructors = $course->events->map(function ($event) {
        return $event->user;
    })->unique();

    return $instructors;
}

function getInstructorsFromWebinarHome($webinar)
{
    $instructors = $webinar->events->map(function ($event) {
        return $event->instructor;
    })->unique();

    return $instructors;
}


function getAllCapacity($course)
{
    $capacity = $course->events->sum(function ($event) {
        return $event->room->capacity;
    });

    return $capacity;
}

function getCountAllParticipants($course)
{
    $participant = $course->events->sum(function ($event) {
        return $event->participants_count;
    });

    return $participant;
}

function getCountAllParticipantsWebinar($webinar)
{
    $participant = $webinar->events->sum(function ($event) {
        return $event->certifications_count;
    });

    return $participant;
}

// ------------------------------------


function getCleanArrayAnswers($string)
{
    return array_map(function ($x) {
        return trim(strtoupper($x));
    }, explode(',', $string));
}


// CONFIG WHATSAPP

function getWhatsappConfig()
{
    $config = Config::select('whatsapp_number', 'whatsapp_message')->first();

    if (!$config) {
        $config = new Config([
            'whatsapp_number' => env('WSP_CONTACT_NUMBER'),
            'whatsapp_message' => env('WSP_CONTACT_MESSAGE'),
        ]);
    }

    return $config;
}


function getConfig()
{
    return Config::first();
}



function getFileExtension(ModelsFile $file)
{
    $pathToFile = $file->file_url;

    $fileExt = FacadesFile::extension($pathToFile);

    switch ($fileExt) {
        case 'ai':
            $extension = 'ai';
            break;
        case 'avi':
            $extension = 'avi';
            break;
        case 'csv':
            $extension = 'csv';
            break;
        case 'eps':
            $extension = 'eps';
            break;
        case 'docx':
        case 'doc':
            $extension = 'docx';
            break;
        case 'flv':
            $extension = 'flv';
            break;
        case 'gif':
            $extension = 'gif';
            break;
        case 'html':
            $extension = 'html';
            break;
        case 'java':
            $extension = 'java';
            break;
        case 'jpg':
        case 'jpeg':
        case 'jfif':
        case 'pjeg':
        case 'pjp':
            $extension = 'jpg';
            break;
        case 'mid':
        case 'midi':
            $extension = 'mid';
            break;
        case 'mov':
            $extension = 'mov';
            break;
        case 'mp4':
        case 'm4p':
        case 'm4v':
            $extension = 'mp4';
            break;
        case 'mpeg':
        case 'mpg':
        case 'mp2':
        case 'mpe':
        case 'mpv':
        case 'm2v':
            $extension = 'mpeg';
            break;
        case 'pdf':
            $extension = 'pdf';
            break;
        case 'png':
            $extension = 'png';
            break;
        case 'pptx':
        case 'pptm':
        case 'ppt':
            $extension = 'ppt';
            break;
        case 'psd':
            $extension = 'psd';
            break;
        case 'rar':
            $extension = 'rar';
            break;
        case 'rss':
            $extension = 'rss';
            break;
        case 'svg':
            $extension = 'svg';
            break;
        case 'txt':
            $extension = 'txt';
            break;
        case 'wav':
            $extension = 'wav';
            break;
        case 'vma':
            $extension = 'vma';
            break;
        case 'xml':
            $extension = 'xml';
            break;
        case 'xlsx':
        case 'xls':
            $extension = 'xsl';
            break;
        case 'zip':
            $extension = 'zip';
            break;
        default:
            $extension = 'default';
    }

    return $extension;
}
