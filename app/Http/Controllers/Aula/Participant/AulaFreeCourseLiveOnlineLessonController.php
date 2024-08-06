<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Event;
use Illuminate\Http\Request;
use Auth;

class AulaFreeCourseLiveOnlineLessonController extends Controller
{

    public function index(Course $course)
    {
        $user = Auth::user();

        $query = $course->events()
            ->doesntHave('courseModule')
            ->with('course', 'user')
            ->where([
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

        $events = $query->get();

        return view('aula.common.live-free-courses.onlinelessons.index',
            compact(
                'course',
                'events',
                'user'
            )
        );
    }

    public function show(Event $event)
    {
        $room = $event->room;
        $course = $event->course;

        return view('aula.common.live-free-courses.onlinelessons.show',
            compact(
                'room',
                'course'
            )
        );
    }

}
