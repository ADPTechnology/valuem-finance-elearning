<?php

namespace App\Http\Controllers\Aula\Instructor;

use App\Http\Controllers\Controller;
use App\Models\{GroupEvent};
use App\Services\{GroupEventService};
use Auth;
use Illuminate\Http\Request;

class AulaSpecCoursesInsController extends Controller
{
    private $groupEventService;

    public function __construct(GroupEventService $service)
    {
        $this->groupEventService = $service;
    }

    public function showGroupEvent(GroupEvent $groupEvent)
    {
        $user = Auth::user();

        $groupEvent->load([
            'specCourse',
            'specCourse.modules' => function ($q) use ($user, $groupEvent) {
                $q->with(
                    [
                        'events' => function ($q) use ($user, $groupEvent) {
                            $q->with(['room:id,description'])
                            ->where('events.user_id', $user->id)
                            ->where('events.active', 'S')
                            ->whereHas('groupEvent', function ($q) use ($groupEvent)  {
                                $q->where('group_events.id', $groupEvent->id);
                            });
                        }
                    ])->withCount('files')
                ->whereHas('events', function ($q) use ($user, $groupEvent) {
                    $q->where('events.user_id', $user->id)
                        ->whereHas('groupEvent', function ($q) use ($groupEvent) {
                            $q->where('group_events.id', $groupEvent->id);
                        });
                });
            },
        ]);

        return view('aula.instructor.specCourses.groupEvents.index', compact(
            'groupEvent'
        ));
    }
}
