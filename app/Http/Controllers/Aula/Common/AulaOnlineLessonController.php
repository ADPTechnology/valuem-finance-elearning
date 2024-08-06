<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;

use App\Models\{Course, Event};
use Auth;

class AulaOnlineLessonController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    public function index(Course $course)
    {
        $user = Auth::user();

        $query = Event::whereHas('course', function ($q) use ($course) {
                                    $q->where([
                                        ['courses.id', '=' ,$course->id],
                                        ['courses.active', '=' , 'S']
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

        return view('aula.common.courses.onlinelessons.index', [
            'course' => $course,
            'events' => $events
        ]);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function show(Event $event)
    {
        $course = getCourseFromEvent($event);
        $room = $event->room;

        return view('aula.common.courses.onlinelessons.viewlesson', [
            'course' => $course,
            'room' => $room
        ]);
    }
}
