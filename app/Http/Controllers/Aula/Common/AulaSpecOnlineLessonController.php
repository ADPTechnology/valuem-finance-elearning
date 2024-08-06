<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\{Event, SpecCourse};
use Auth;
use Illuminate\Http\Request;

class AulaSpecOnlineLessonController extends Controller
{
    public function index(SpecCourse $specCourse)
    {
        $user = Auth::user();

        $query = $specCourse->groupEvents()
            ->where('group_events.active', 'S')
            ->whereHas('specCourse', function ($q) {
                $q->where('spec_courses.active', 'S');
            })
            ->with([
                'events' => function ($q) use ($user) {
                    $q->where('events.active', 'S')
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

        $eventGroups = $query->get();

        return view('aula.common.specCourses.onlinelessons.index', compact(
            'specCourse',
            'eventGroups'
        ));
    }

    public function show(Event $event)
    {
        $room = $event->room;
        $specCourse = $event->groupEvent->specCourse;

        return view('aula.common.specCourses.onlinelessons.show',
            compact(
                'room',
                'specCourse'
            )
        );
    }
}
