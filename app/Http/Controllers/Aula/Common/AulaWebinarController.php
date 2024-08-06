<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Services\WebinarService;
use Auth;
use Illuminate\Http\Request;

class AulaWebinarController extends Controller
{
    protected $webinarService;

    public function __construct(WebinarService $service)
    {
        $this->webinarService = $service;
    }


    public function indeX()
    {
        $webinars = $this->webinarService->getWebinars();

        return view('aula.common.webinars.index', compact('webinars'));
    }


    public function show(Webinar $webinar)
    {
        $user = Auth::user();

        if ($user->role == 'instructor') {
            $webinar->load(
                [
                    'events' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                ]
            );
        }

        return view('aula.common.webinars.show', compact('webinar'));
    }
}
