<?php

namespace App\Http\Controllers\Aula\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Event;
use App\Models\SpecCourse;
use App\Models\User;
use App\Models\Webinar;
use Auth;
use Illuminate\Http\Request;

class AulaInstructorInformationController extends Controller
{



    // E-LEARNING

    public function index(Course $course, User $instructor)
    {

        $user = Auth::user();
        $instructor->loadAvatar();

        $query = Event::where('user_id', $instructor->id)
            ->whereHas('course', function ($q) use ($course) {
                $q->where([
                    ['courses.id', '=', $course->id],
                    ['courses.active', '=', 'S']
                ]);
            })
            ->with('user')
            ->where('date', getCurrentDate())
            ->doesntHave('courseModule');

        if ($user->role == 'participants') {
            $query->whereHas('certifications', function ($q2) use ($user) {
                $q2->where('certifications.user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        $events = $query->get();

        $instructor->userDetail()->firstOrCreate([]);

        $instructor->load('userDetail');


        return view('aula.common.instructorInformation.index', [
            'course' => $course,
            'events' => $events,
            'instructor' => $instructor
        ]);

    }

    // SPEC COURSES 
    public function getInformationAboutSpecCourse(User $instructor, SpecCourse $specCourse)
    {
        $user = Auth::user();

        $instructor->loadAvatar();

        $query = $specCourse->groupEvents()
            ->where('group_events.active', 'S')
            ->whereHas('specCourse', function ($q) {
                $q->where('spec_courses.active', 'S');
            })
            ->with([
                'events' => function ($q) use ($user, $instructor) {
                    $q->where('user_id', $instructor->id)
                        ->where('events.active', 'S')
                        ->where('date', getCurrentDate())
                        ->where(function ($q) use ($user) {

                            if ($user->role == 'instructor') {
                                $q->where('user_id', $user->id);
                            } else {
                                $q->whereHas('certifications', function ($q2) use ($user) {
                                    $q2->where('certifications.user_id', $user->id);
                                });
                            }

                        });
                }
            ]);

        $instructor->userDetail()->firstOrCreate([]);

        $instructor->load('userDetail');

        $eventGroups = $query->get();

        return view('aula.common.specCourses.instructorInformation.index', [
            'specCourse' => $specCourse,
            'eventGroups' => $eventGroups,
            'instructor' => $instructor
        ]);
    }


    // FREE COURSE LIVE

    public function getInformationAboutFreeCourseLive(User $instructor, Course $course)
    {

        $user = Auth::user();

        $instructor->loadAvatar();

        $query = $course->events()
            ->with('course', 'user')
            ->where([
                'user_id' => $instructor->id,
                'events.active' => 'S',
                'date' => getCurrentDate()
            ])
            ->whereHas('course', function ($q) {
                $q->where('courses.active', 'S');
            });

        if ($user->role == 'instructor') {
            $query->where('user_id', $user->id);

        } else {
            $query->whereHas('certifications', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $instructor->userDetail()->firstOrCreate([]);

        $instructor->load('userDetail');

        $events = $query->get();


        return view('aula.common.live-free-courses.instructorInformation.index', [
            'course' => $course,
            'events' => $events,
            'instructor' => $instructor,
            'user' => $user
        ]);

    }


    // WEBINAR


    public function getInformationAboutWebinar(User $instructor, Webinar $webinar)
    {
        $user = Auth::user();

        $instructor->loadAvatar();

        $query = $webinar->events()
            ->with('webinar', 'instructor')
            ->where([
                'active' => 'S',
                'user_id' => $instructor->id,
                'date' => getCurrentDate()
            ])
            ->whereHas('webinar', function ($q) {
                $q->where('active', 'S');
            });

        if ($user->role == 'instructor') {

            $query->where('user_id', $user->id);

        } else {

            $query->whereHas('certifications', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        }

        $instructor->userDetail()->firstOrCreate([]);

        $instructor->load('userDetail');

        $events = $query->get();


        return view('aula.common.webinars.instructorInformation.index',compact(
                'webinar',
                'events',
                'user',
                'instructor'
            )
        );
    }




}
