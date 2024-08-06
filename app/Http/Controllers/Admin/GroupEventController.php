<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GroupEventService;
use Illuminate\Http\Request;

class GroupEventController extends Controller
{

    protected $groupEventService;

    public function __construct(GroupEventService $groupEventService)
    {
        $this->groupEventService = $groupEventService;
    }

    public function index(Request $request)
    {

        if($request->ajax())
        {
            return $this->groupEventService->getDatatable();
        }

        return view('admin.group_event.index');
    }

}
