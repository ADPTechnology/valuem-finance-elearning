<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\FreeCourseLiveService;
use Auth;

class AulaFreeCourseLiveController extends Controller
{

    protected $freeCourseLiveService;

    public function __construct(FreeCourseLiveService $service)
    {
        $this->freeCourseLiveService = $service;
    }


    public function index()
    {
        $freeCourses = $this->freeCourseLiveService->getFreeCoursesLive();

        return view('aula.viewParticipant.live-free-courses.index', compact('freeCourses'));
    }


    public function show(Course $course)
    {
        $user = Auth::user();

        if ($user->role == 'instructor') {
            $course->load(
                [
                    'events' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                ]
            );
        }

        return view('aula.viewParticipant.live-free-courses.show', compact('course'));
    }



}
