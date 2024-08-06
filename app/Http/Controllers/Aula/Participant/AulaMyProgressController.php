<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Services\{CourseService, FreeCourseLiveService, FreeCourseService, SpecCourseService, WebinarService};
use Auth;

class AulaMyProgressController extends Controller
{
    private $freeCourseService;
    private $courseService;
    private $specCourseService;
    private $webinarService;
    private $freeCourseLiveService;

    public function __construct(FreeCourseService $fcService, CourseService $cService, SpecCourseService $scService, WebinarService $webinarService, FreeCourseLiveService $fclService)
    {
        $this->freeCourseService = $fcService;
        $this->courseService = $cService;
        $this->specCourseService = $scService;
        $this->webinarService = $webinarService;
        $this->freeCourseLiveService = $fclService;
    }

    public function index()
    {
        $user = Auth::user();

        $courses = $this->courseService->getCoursesBasedOnRole($user);

        $liveFreeCourses = $this->freeCourseLiveService->getFreeCoursesLive();

        $freeCourses = $this->freeCourseService->withFreeCourseRelationshipsQuery()
            ->where('active', 'S')
            ->with([
                'productCertifications' => fn ($query1) =>
                $query1->where([
                    'user_id' => Auth::user()->id,
                    'flg_finished' => 'S',
                    'status' => 'approved',
                ])
                //->where('score', '>=', 14),
            ])
            ->with('courseChapters.progressUsers', function ($q) use ($user) {
                $q->wherePivot('user_id', $user->id);
            })
            ->whereHas('courseChapters.progressUsers', function ($query) use ($user) {
                $query->where('user_course_progress.user_id', $user->id);
            })
            ->get();

        $specCourses = $this->specCourseService->getSpecCourses();

        $webinars = $this->webinarService->getWebinars();

        return view(
            'aula.viewParticipant.myprogress.index',
            compact(
                'courses',
                'freeCourses',
                'liveFreeCourses',
                'specCourses',
                'webinars',
                'user'
            )
        );
    }
}
