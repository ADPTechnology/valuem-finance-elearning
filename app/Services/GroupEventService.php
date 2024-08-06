<?php

namespace App\Services;

use App\Models\GroupEvent;
use App\Models\SpecCourse;
use Exception;
use Illuminate\Http\Request;

class GroupEventService
{

    public function getDatatable()
    {
        $query = GroupEvent::withCount(['group_participant']);

        return datatables()->of($query)
            ->addColumn('title', function ($groupEvent) {
                return $groupEvent->title;
            })
            ->addColumn('description', function ($groupEvent) {
                return $groupEvent->description;
            })
            ->addColumn('active', function ($groupEvent) {
                return $groupEvent->description;
            })
            ->addColumn('action', function ($groupEvent) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $groupEvent->id . '" data-url="' . route('admin.events.update', $groupEvent) . '"
                                        data-send="' . route('admin.events.edit', $groupEvent) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editEvent-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $groupEvent->group_participant_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $groupEvent->id . '" data-original-title="delete"
                                            data-url="' . route('admin.events.destroy', $groupEvent) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteEvent-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request, SpecCourse $specCourse)
    {
        $data = $request->all();

        $data['active'] = $request['active'] == 'on' ? 'S' : 'N';

        if ($groupEvent = $specCourse->groupEvents()->create($data)) {
            return $groupEvent;
        }

        throw new Exception(config('parameters.exception_message'));

    }


    public function edit(GroupEvent $groupEvent)
    {
        return $groupEvent;
    }

    public function update(GroupEvent $groupEvent, Request $request)
    {
        $data = $request->all();

        $data['active'] = $request['active'] == 'on' ? 'S' : 'N';

        if ($groupEvent->update($data)) {
            return $groupEvent;
        }

        throw new Exception(config('parameters.exception_message'));

    }




    // grupos de eventos de un curso especifico

    public function getGroupsSpecCourse($specCourse)
    {
        $groupEventsForSpecCourse = GroupEvent::where('spec_course_id', $specCourse->id)
            ->withCount('participantGroups', 'events');

        return datatables()->of($groupEventsForSpecCourse)

            ->addColumn('id', function ($groupEvent) {
                return $groupEvent->id;
            })
            ->addColumn('title', function ($groupEvent) {

                return '<a href="' . route('admin.specCourses.groupEvents.show', $groupEvent) . '">' . $groupEvent->title . '</a>';
            })
            ->addColumn('description', function ($groupEvent) {
                return $groupEvent->description ?? '-';
            })
            ->addColumn('active', function ($groupEvent) {

                $isActive = $groupEvent->active == 'S' ? 'active' : 'inactive';
                $nameActive = $groupEvent->active == 'S' ? 'Activo' : 'Inactivo';

                $span = '<span class="status ' . $isActive . '">' . $nameActive . '</span>';

                return $span;
            })
            ->addColumn('action', function ($groupEvent) {

                $btn = '
                    <button id="container-btn-edit" data-toggle="modal" data-id="' . $groupEvent->id . '"

                        data-url="' . route('admin.specCourses.groupEvents.update', $groupEvent) . '"
                        data-send="' . route('admin.specCourses.groupEvents.edit', $groupEvent) . '"
                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                        editGroupEvent-btn">

                        <i class="fa-solid fa-pen-to-square"></i>

                    </button>';

                if (
                    $groupEvent->participant_groups_count == 0 && $groupEvent->events_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $groupEvent->id . '" data-original-title="delete"
                                        data-place="index"
                                        data-url="' . route('admin.specCourses.groupEvents.destroy', $groupEvent) . '" class="ms-3 delete btn btn-danger btn-sm
                                        deleteGroupEvent-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'active', 'action'])
            ->make(true);

    }

}


