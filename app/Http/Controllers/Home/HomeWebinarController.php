<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Services\HomeWebinarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeWebinarController extends Controller
{

    protected $webinarService;

    public function __construct(HomeWebinarService $webinarService)
    {
        $this->webinarService = $webinarService;
    }

    public function index()
    {
        $webinars = $this->webinarService->getAvailableWebinars();

        return view('home.webinar.index', compact('webinars'));
    }


    public function show(Webinar $webinar)
    {
        $events = $this->webinarService->getAvailableEvents($webinar);

        return view('home.webinar.show',
            compact(
                'webinar',
                'events'
            )
        );
    }
}
