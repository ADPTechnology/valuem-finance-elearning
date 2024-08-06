<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\{Course, Event};
use App\Services\{CertificationService, EventService};
use Auth;
use Illuminate\Http\Request;

class AulaEventsSecurController extends Controller
{
    private $eventService;
    private $certificationService;

    public function __construct(EventService $eventService, CertificationService $certificationService)
    {
        $this->eventService = $eventService;
        $this->certificationService = $certificationService;
    }

    public function index(Request $request, Course $course) 
    {
        $user = Auth::user();
        $user->load('miningUnits');

        if ($request->ajax()) {

            if ($request->has('dynamic_columns') && $request['dynamic_columns']) {

                $columns = array();
                foreach ($user->miningUnits as $miningUnit) {
                    if (getMiningUnitSufix($miningUnit->description) == 'P') {
                        array_push($columns, 'signature_p');
                    }
                    if (getMiningUnitSufix($miningUnit->description) == 'A') {
                        array_push($columns, 'signature_a');
                    }
                }

                return response()->json([
                    'columns' => $columns
                ]);
            }

            return $this->eventService->getSecurityEventsDatatable($user, $course);
        }   
        
        return view('aula.security.events.index', compact(
            'course',
            'user'
        ));
    }  
    
    public function show(Request $request, Event $event)
    {
        if ($request->ajax()) {
            return $this->certificationService->getSecurityParticipantsDatatable($event);
        }

        $event->load('course');

        return view('aula.security.events.participants', compact(
            'event'
        ));
    }
}
