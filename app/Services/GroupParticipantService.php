<?php

namespace App\Services;

use App\Models\GroupEvent;
use App\Models\ParticipantGroup;
use App\Models\User;
use DataTables;
use Exception;
use Illuminate\Http\Request;

class GroupParticipantService
{

    public function getDatatable(GroupEvent $groupEvent)
    {

        $query = $groupEvent->participantGroups()->withCount('participants');

        return DataTables::of($query)
            ->addColumn('title', function ($groupParticipant) {
                return '<a href="' . route('admin.specCourses.groupEvents.groupParticipants.show', $groupParticipant) . '">' . $groupParticipant->title . '</a>';
            })
            ->addColumn('description', function ($groupParticipant) {
                return $groupParticipant->description ?? '-';
            })
            ->addColumn('active', function ($groupParticipant) {

                $isActive = $groupParticipant->active == 'S' ? 'active' : 'inactive';
                $nameActive = $groupParticipant->active == 'S' ? 'Activo' : 'Inactivo';

                $span = '<span class="status ' . $isActive . '">' . $nameActive . '</span>';

                return $span;

            })
            ->addColumn('created_at', function ($groupParticipant) {
                return $groupParticipant->created_at;
            })
            ->addColumn('updated_at', function ($groupParticipant) {
                return $groupParticipant->updated_at;
            })
            ->addColumn('action', function ($groupParticipant) {

                $btn = '
                    <button data-toggle="modal" data-id="' . $groupParticipant->id . '"

                        data-url="' . route('admin.specCourses.groupEvents.groupParticipants.update', $groupParticipant) . '"
                        data-send="' . route('admin.specCourses.groupEvents.groupParticipants.edit', $groupParticipant) . '"
                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                        editGroupParticipant-btn">
                        <i class="fa-solid fa-pen-to-square"></i>

                    </button>';


                if ($groupParticipant->participants_count == 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $groupParticipant->id . '" data-original-title="delete"
                                        data-place="index"
                                        data-url="' . route('admin.specCourses.groupEvents.groupParticipants.destroy', $groupParticipant) . '" class="delete btn btn-danger btn-sm
                                        deleteGroupParticipant-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;

            })
            ->rawColumns(['title', 'active', 'action'])
            ->make(true);

    }

    public function store(GroupEvent $groupEvent, Request $request)
    {

        $data = $request->all();

        $data['active'] = $request['active'] == 'on' ? 'S' : 'N';

        if ($groupParticipant = $groupEvent->participantGroups()->create($data)) {
            return $groupParticipant;
        }

        throw new Exception(config('parameters.exception_message'));

    }

    public function update(ParticipantGroup $groupParticipant, Request $request)
    {

        $data = $request->all();

        $data['active'] = $request['active'] == 'on' ? 'S' : 'N';

        if ($groupParticipant->update($data)) {
            return $groupParticipant;
        }

        throw new Exception(config('parameters.exception_message'));

    }


    public function destroy(ParticipantGroup $groupParticipant)
    {

        if ($groupParticipant->delete()) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));

    }

    public function getParticipants(ParticipantGroup $groupParticipant)
    {

        $query = $groupParticipant->participants();

        return DataTables::of($query)
            ->addColumn('id', function ($participant) {
                return $participant->id;
            })
            ->addColumn('dni', function ($participant) {
                return $participant->dni;
            })
            ->addColumn('name', function ($participant) {
                return $participant->full_name_complete;
            })
            ->addColumn('action', function ($participant) use ($groupParticipant) {

                $btn = '<a href="javascript:void(0)" data-id="' . $participant->participant_id . '" data-original-title="delete"
                                        data-place="index"
                                        data-url="' . route('admin.specCourses.groupEvents.groupParticipants.deleteParticipantOnGroup', ['participant' => $participant->participant_id, 'groupParticipant' => $groupParticipant]) . '" class="delete btn btn-danger btn-sm
                                        deleteParticipant-btn"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;

            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function getPotentialParticipants(Request $request, ParticipantGroup $groupParticipant)
    {

        $groupEvent = $groupParticipant->groupEvent()->first();

        $participantsOnGroup = $groupParticipant->participants()->pluck('participant_id')->toArray();

        $participantsInAnyGroup = ParticipantGroup::where('group_event_id', $groupEvent->id)
            ->get()
            ->flatMap->participants
            ->pluck('id')
            ->toArray();

        $allParticipants = $groupEvent->events()
            ->with(['participants.company'])
            ->orderBy('id', 'DESC')
            ->get()
            ->flatMap->participants
            ->whereNotIn('id', $participantsOnGroup)
            ->whereNotIn('id', $participantsInAnyGroup)
            ->unique('id')
            ->values();

        if ($request->filled('search_company')) {
            $allParticipants = $allParticipants->where('company_id', $request['search_company']);
        }

        $allParticipants = $allParticipants->all();

        return DataTables::of($allParticipants)
            ->addColumn('choose', function ($user) {
                $checkbox = '<div class="custom-checkbox custom-control">
                    <input type="checkbox" name="users-selected[]"
                     class="custom-control-input checkbox-user-input" id="checkbox-' . $user->id . '" value="' . $user->dni . '">
                    <label for="checkbox-' . $user->id . '" class="custom-control-label checkbox-user-label">&nbsp;</label>
                </div>';
                return $checkbox;
            })
            ->editColumn('company', function ($user) {
                return $user->company != null ? $user->company->description : '-';
            })
            ->editColumn('user.name', function ($user) {
                return $user->full_name;
            })
            ->rawColumns(['choose'])
            ->make(true);

    }


    public function addParticipantsOnGroup(Request $request, ParticipantGroup $groupParticipant): bool
    {
        $dnis = $request['users-selected'];

        $users = User::select('id')->whereIn('dni', $dnis)->get();

        $groupParticipant->participants()->attach($users);

        return true;

    }


    public function deleteParticipantOnGroup(User $participant, ParticipantGroup $groupParticipant)
    {

        $groupParticipant->participants()->detach($participant);

        return true;

    }






}


