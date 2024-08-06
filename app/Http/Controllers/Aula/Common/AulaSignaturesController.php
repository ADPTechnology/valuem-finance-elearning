<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\{Event};
use App\Services\{EventService, UserService};
use Auth;
use Exception;
use Illuminate\Http\Request;

class AulaSignaturesController extends Controller
{
    private $userService;
    private $eventService;

    public function __construct(UserService $userService, EventService $eventService)
    {
        $this->userService = $userService;
        $this->eventService = $eventService;
    }

    public function index()
    {
        return view('aula.common.signatures.index');
    }

    public function indexSecurity(Event $event, $miningUnit)
    {
        $event->load('course');

        return view('aula.security.signatures.index', compact(
            'event',
            'miningUnit'
        ));
    }

    public function create()
    {
        return view('aula.common.signatures.create');
    }

    public function createSecurity(Event $event, $miningUnit)
    {
        return view('aula.security.signatures.create', compact(
            'event',
            'miningUnit'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $storage = env('FILESYSTEM_DRIVER');

        try{
            $success = $this->userService->storeSignature($user, $request->get('imgBase64'), $storage);
        }catch(Exception $e){
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');
        $route = $success ? route('aula.signatures.index') : null;

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route
        ]);
    }

    public function storeSecurity(Request $request, Event $event, $miningUnit)
    {
        $user = Auth::user();

        $event->load('course');
        $user->load('miningUnits');

        $storage = env('FILESYSTEM_DRIVER');

        try{
            $success = $this->eventService->storeSignatureSecurity($user, $request->get('imgBase64'), $event, $miningUnit, $storage);
        }catch(Exception $e){
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');
        $route = $success ? route('aula.course.events.security.index', ['course' => $event->course]) : null;

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route
        ]);
    }
}
