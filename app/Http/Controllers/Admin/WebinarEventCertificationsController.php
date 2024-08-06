<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebinarCertification;
use App\Models\WebinarEvent;
use App\Services\{WebinarEventCertificationService};
use Exception;
use Illuminate\Http\Request;

class WebinarEventCertificationsController extends Controller
{
    private $webinarEventCertificationService;

    public function __construct(WebinarEventCertificationService $service)
    {
        $this->webinarEventCertificationService = $service;
    }

    public function index(Request $request, WebinarEvent $webinarEvent)
    {
        return $this->webinarEventCertificationService->getParticipantsDataTable($request, $webinarEvent);
    }

    public function getUsersList(Request $request, WebinarEvent $webinarEvent)
    {
        return $this->webinarEventCertificationService->getUsersTable($request, $webinarEvent);
    }

    public function store(Request $request, WebinarEvent $webinarEvent)
    {
        $webinarEvent->load([
                                'certifications' => fn ($q) =>
                                    $q->select('webinar_certifications.id',
                                                'webinar_certifications.user_id',
                                                'webinar_certifications.webinar_event_id'
                                            )
                                        ->with('user:id,dni'),
                                'room'
                            ]);

        $dnis = $request['users-selected'];

        try{
            $info = $this->webinarEventCertificationService->store($dnis, $webinarEvent);
        } catch (Exception $e) {
            $info = array("success" => false, "status" => "error", "note" => config('parameters.exception_message'));
        }

        $message = getMessageFromSuccess($info['success'], 'stored');

        $webinarEvent->load(['instructor', 'webinar'])
                    ->loadCount(['certifications']);
        $html = view('admin.webinars.events.partials.components._event_box', compact('webinarEvent'))->render();

        return response()->json([
            "success" => $info['success'],
            "status" => $info['status'],
            "note" => $info['note'],
            "message" => $message,
            "html" => $html,
        ]);
    }

    public function updateUnlock(Request $request, WebinarCertification $webinarCertification)
    {
        try{
            $success = $this->webinarEventCertificationService->updateUnlockCert($request, $webinarCertification);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function destroy(WebinarCertification $webinarCertification)
    {
        $html = null;

        try{
            $success = $this->webinarEventCertificationService->destroy($webinarCertification);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        if ($success) {
            $webinarEvent = $webinarCertification->event;
            $webinarEvent->load(['instructor', 'webinar'])
                        ->loadCount(['certifications']);
            $html = view('admin.webinars.events.partials.components._event_box', compact('webinarEvent'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }
}
