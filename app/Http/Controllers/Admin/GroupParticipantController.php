<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\GroupEvent;
use App\Models\ParticipantGroup;
use App\Models\User;
use App\Services\GroupParticipantService;
use Exception;
use Illuminate\Http\Request;

class GroupParticipantController extends Controller
{

    protected $groupParticipantService;

    public function __construct(GroupParticipantService $groupParticipantService)
    {
        $this->groupParticipantService = $groupParticipantService;
    }


    public function getDatatable(GroupEvent $groupEvent)
    {
        return $this->groupParticipantService->getDatatable($groupEvent);
    }

    public function store(GroupEvent $groupEvent, Request $request)
    {

        try {

            $groupParticipants = $this->groupParticipantService->store($groupEvent, $request);
            $success = $groupParticipants ? true : false;
            $message = $success ? getMessageFromSuccess($success, 'stored') : config('parameters.exception_message');

        } catch (Exception $th) {
            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function show(Request $request, ParticipantGroup $groupParticipant)
    {

        if ($request->ajax()) {
            return $this->groupParticipantService->getParticipants($groupParticipant);
        }
        $companies = Company::all();
        $groupParticipant->load('participants', 'groupEvent', 'groupEvent.specCourse');

        return view('admin.groupParticipant.show', compact('groupParticipant', 'companies'));
    }


    public function edit(ParticipantGroup $groupParticipant)
    {
        return response()->json([
            'success' => true,
            'groupParticipant' => $groupParticipant
        ]);
    }

    public function update(ParticipantGroup $groupParticipant, Request $request)
    {
        try {

            $groupParticipant = $this->groupParticipantService->update($groupParticipant, $request);
            $success = $groupParticipant ? true : false;
            $message = $success ? getMessageFromSuccess($success, 'updated') : config('parameters.exception_message');

        } catch (Exception $th) {
            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);

    }

    public function destroy(ParticipantGroup $groupParticipant)
    {
        try {

            $success = $groupParticipant->delete();
            $message = $success ? getMessageFromSuccess($success, 'deleted') : config('parameters.exception_message');

        } catch (Exception $th) {

            $success = false;
            $message = config('parameters.exception_message');

        }

        return response()->json([
            "success" => $success,
            'message' => $message,
        ]);
    }


    public function getPotentialParticipants(Request $request, ParticipantGroup $groupParticipant)
    {
        return $this->groupParticipantService->getPotentialParticipants($request, $groupParticipant);
    }


    public function addParticipantsOnGroup(Request $request, ParticipantGroup $groupParticipant)
    {
        try {

            $success = $this->groupParticipantService->addParticipantsOnGroup($request, $groupParticipant);
            $message = config('parameters.stored_message');

        } catch (Exception $th) {

            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            "success" => $success,
            'message' => $message,
        ]);

    }


    public function deleteParticipantOnGroup(User $participant, ParticipantGroup $groupParticipant)
    {
        try {

            $success = $this->groupParticipantService->deleteParticipantOnGroup($participant, $groupParticipant);
            $message = config('parameters.deleted_message');

        } catch (Exception $th) {

            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            "success" => $success,
            'message' => $message,
        ]);
    }


}
