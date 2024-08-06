<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\WebinarEvent;
use App\Services\WebinarEventService;
use Illuminate\Http\Request;

class AulaWebinarEventController extends Controller
{
    private $webinarEventService;

    public function __construct(WebinarEventService $service)
    {
        $this->webinarEventService = $service;
    }

    public function show(Request $request, WebinarEvent $event)
    {

        if ($request->ajax()) {
            return $this->webinarEventService->getParticipantsDataTable($event);
        }

        $event->load(['webinar']);

        return view('aula.common.webinars.events.show', compact('event'));
    }
}
