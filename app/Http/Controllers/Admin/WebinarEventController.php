<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebinarEventRequest;
use App\Models\{Company, Room, User, Webinar, WebinarEvent};
use App\Services\{WebinarEventService};
use Exception;
use Illuminate\Http\Request;

class WebinarEventController extends Controller
{
    private $webinarEventService;

    public function __construct(WebinarEventService $service)
    {
        $this->webinarEventService = $service;
    }

    public function index(Request $request, Webinar $webinar)
    {
        return $this->webinarEventService->getDataTable($webinar);
    }

    public function create(Request $request)
    {
        $collection = collect();

        if ($request['column'] == 'room') {
            $collection = Room::get(['id', 'description']);
        } else if ($request['column'] == 'instructor') {
            $collection = User::where('role', 'instructor')
                ->get(['id', 'name', 'paternal'])
                ->append('full_name');
        }
        else if ($request['column'] == 'responsable') {
            $collection = User::getResponsablesQuery()
                            ->get(['id', 'name', 'paternal'])
                            ->append('full_name');
        }

        return response()->json($collection);
    }

    public function store(WebinarEventRequest $request, Webinar $webinar)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $webinarEvent = $this->webinarEventService->store($request, $webinar, $storage);
            $success = $webinarEvent ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        $route = $request['verifybtn'] == 'show' ? route('admin.webinars.all.events.show', $webinarEvent) : null;
        $show = $route ? true : false;

        if ($request['place'] == 'index') {
            $webinar->loadCount([
                'files' => function ($q) {
                    $q->where('file_type', 'archivos');
                },
                'events'
            ]);
            $webinar->loadImage();
            $html = view('admin.webinars.partials.components._webinar_box', compact('webinar'))->render();
        } else {
            $html = null;
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route,
            "html" => $html,
            "show" => $show,
        ]);
    }

    public function show(WebinarEvent $webinarEvent)
    {
        $webinarEvent->load(
            [
                'instructor',
                'room',
                'webinar',
                'file' => fn ($q) =>
                $q->where('file_type', 'imagenes')
            ]
        )
            ->loadCount('certifications');

        $companies = Company::get(['id', 'description']);

        return view('admin.webinars.events.show', compact(
            'webinarEvent',
            'companies'
        ));
    }

    public function edit(WebinarEvent $webinarEvent)
    {
        $webinarEvent->time_start = getTimeforHummans($webinarEvent->time_start);
        $webinarEvent->time_end = getTimeforHummans($webinarEvent->time_end);
        $webinarEvent->load(
            [
                'instructor' => fn ($q) =>
                $q->select('id', 'name', 'paternal'),
                'responsable' => fn ($q) =>
                $q->select('id', 'name', 'paternal'),
                'room' => fn ($q) =>
                $q->select('id', 'description'),
                'file' => fn ($q) =>
                $q->where('file_type', 'imagenes')
            ]
        );
        $webinarEvent->instructor->append('full_name');

        if ($webinarEvent->responsable) {
            $webinarEvent->responsable->append('full_name');
        }
        return response()->json([
            "webinarEvent" => $webinarEvent,
            "image" => $webinarEvent->file ? verifyImage($webinarEvent->file) : null
        ]);
    }

    public function update(WebinarEventRequest $request, WebinarEvent $webinarEvent)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $webinarEvent = $this->webinarEventService->update($request, $webinarEvent, $storage);
            $success = $webinarEvent ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($request['place'] == 'show') {

            $webinarEvent->loadRelationships();

            $html = view('admin.webinars.events.partials.components._event_box', compact('webinarEvent'))->render();
            $show = true;
        }

        return response()->json([
            "message" => $message,
            "success" => $success,
            "html" => $html ?? null,
            "show" => $show ?? false,
            'title' => mb_strtolower($webinarEvent->description, 'UTF-8'),
        ]);
    }

    public function destroy(Request $request, WebinarEvent $webinarEvent)
    {
        $show = false;
        $route = null;

        $storage = env('FILESYSTEM_DRIVER');

        $webinarEvent->load('webinar');
        $webinar = $webinarEvent->webinar;

        try {
            $success = $this->webinarEventService->destroy($webinarEvent, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        if ($request['place'] == 'show') {
            $route = route('admin.webinars.all.show', $webinar);
            $show = true;
            $html = NULL;
        } else {
            $webinar->loadCount([
                'files' => function ($q) {
                    $q->where('file_type', 'archivos');
                },
                'events'
            ]);
            $webinar->loadImage();
            $html =  view('admin.webinars.partials.components._webinar_box', compact('webinar'))->render();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'route' => $route,
            "html" => $html,
            'show' => $show,
        ]);
    }
}
