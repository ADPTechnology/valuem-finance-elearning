<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Models\WebinarEvent;
use Illuminate\Http\Request;
use Auth;

class AulaWebinarOnlineLessonController extends Controller
{
    public function index(Webinar $webinar)
    {
        $user = Auth::user();

        $query = $webinar->events()
            ->with('webinar', 'instructor')
            ->where([
                'active' => 'S',
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


        $events = $query->get();


        return view('aula.common.webinars.onlinelessons.index',
            compact(
                'webinar',
                'events',
                'user'
            )
        );
    }

    public function show(WebinarEvent $event)
    {
        $room = $event->room;
        $webinar = $event->webinar;

        return view(
            'aula.common.webinars.onlinelessons.show',
            compact(
                'room',
                'webinar'
            )
        );
    }
}
