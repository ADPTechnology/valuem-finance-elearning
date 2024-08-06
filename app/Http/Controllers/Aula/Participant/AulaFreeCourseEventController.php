<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Event;
use App\Services\CourseModuleService;
use App\Services\FreeCourseLiveService;
use Auth;
use Exception;
use Illuminate\Http\Request;

class AulaFreeCourseEventController extends Controller
{
    private $freeCourseLiveService;

    public function __construct(FreeCourseLiveService $service)
    {
        $this->freeCourseLiveService = $service;
    }

    public function show(Request $request, Event $event)
    {

        if ($request->ajax()) {
            $user = Auth::user();
            return $this->freeCourseLiveService->getParticipantsDataTable($user, $event);
        }

        $event->load([
            'assignments' => fn($q) =>
                $q->where('active', 'S'),
            'course'
        ]);

        return view(
            'aula.common.live-free-courses.events.show',
            compact(
                'event'
            )
        );
    }


    public function getScoreFin(Certification $certification)
    {
        return response()->json([
            'score' => $certification->score_fin
        ]);
    }

    public function storeScoreFin(Request $request, Certification $certification)
    {

        $success = false;

        try {
            $success = $certification->update([
                'score_fin' => $request->score_fin
            ]);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

}
