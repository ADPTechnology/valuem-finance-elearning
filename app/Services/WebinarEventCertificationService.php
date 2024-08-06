<?php

namespace App\Services;

use App\Models\{User, WebinarCertification, WebinarEvent};
use Auth;
use Datatables;
use Illuminate\Http\Request;

class WebinarEventCertificationService
{
    public function getParticipantsDataTable($request, WebinarEvent $webinarEvent)
    {
        $type = $request['type'];

        $query = $webinarEvent->certifications()
            ->with(['user.company']);

        if ($request['type'] == 'inner') {
            $query->whereHas('user', function ($q) {
                $q->where('user_type', null);
            });
        } else {
            $query->whereHas('user', function ($q) {
                $q->where('user_type', 'external');
            });
        }

        $allWebCertifications = Datatables::of($query)
            ->editColumn('user.name', function ($webinarCertification) {
                return $webinarCertification->user->full_name_complete;
            })
            ->addColumn('enabled', function ($webinarCertification) use ($type) {

                $ext = $type == 'external' ? '-ext' : '';

                $assit_btn = '<label class="custom-switch">
                                <input type="checkbox" name="unlock_cert"
                                    class="custom-switch-input unlock_cert_user_checkbox' . $ext . '"
                                    ' . $webinarCertification->unlock_cert_checked . '
                                    data-url="' . route('admin.webinars.all.events.certifications.updateUnlock', $webinarCertification) . '">
                                <span class="custom-switch-indicator"></span>
                            </label>';

                return $assit_btn;
            })
            ->addColumn('action', function ($webinarCertification) use ($type) {

                $ext = $type == 'external' ? '-ext' : '';

                $btn = '<a href="javascript:void(0)" data-id="' .
                    $webinarCertification->id . '" data-original-title="delete"
                                        data-url="' .
                    route('admin.webinars.all.events.certifications.destroy', $webinarCertification)
                    . '" class="ms-3 edit btn btn-danger btn-sm
                                        deleteWbCertification' . $ext . '">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>';


                return $btn;
            })
            ->rawColumns(['enabled', 'action'])
            ->make(true);

        return $allWebCertifications;
    }

    public function getUsersTable($request, WebinarEvent $webinarEvent)
    {
        $participants = $webinarEvent->certifications()
            ->whereHas('user', function ($q) {
                $q->where('user_type', null);
            })
            ->get(['id', 'user_id', 'webinar_event_id'])

            ->pluck('user_id')->toArray();

        $users = User::where('role', 'participants')
            ->where(function ($query) {
                $query->where('user_type', '!=', 'external')
                    ->orWhereNull('user_type');
            })
            ->whereNotIn('users.id', $participants)
            ->with('company')
            ->select('users.*');

        if ($request->filled('search_company')) {
            $users = $users->where('company_id', $request['search_company']);
        }

        $allUsers = DataTables::of($users)
            ->addColumn('choose', function ($user) {
                $checkbox = '<div class="custom-checkbox custom-control">
                                            <input type="checkbox" name="users-selected[]"
                                             class="custom-control-input checkbox-user-input" id="checkbox-' . $user->id . '" value="' . $user->dni . '">
                                            <label for="checkbox-' . $user->id . '" class="custom-control-label checkbox-user-label">&nbsp;</label>
                                        </div>';
                return $checkbox;
            })
            ->editColumn('company.description', function ($user) {
                return $user->company->description ?? '-';
            })
            ->editColumn('user.name', function ($user) {
                return $user->full_name;
            })
            ->rawColumns(['choose'])
            ->make(true);

        return $allUsers;
    }

    public function store($dnis, WebinarEvent $webinarEvent)
    {
        /** @var self $status */
        /** @var self $note */
        $note = null;
        $status = null;

        $users = $this->getFilteredUsers($dnis, $webinarEvent);

        [$certifications, $status, $note] = $this->getCertificationsArray($users, $webinarEvent);

        $webinarEvent->certifications()->saveMany($certifications);

        return array("success" => true, "status" => $status, "note" => $note);
    }

    public function updateUnlockCert($request, WebinarCertification $webinarCertification)
    {
        $request['unlock_cert'] = $request['unlock_cert'] == 'true' ? 'S' : 'N';

        return $webinarCertification->update($request->only('unlock_cert'));
    }

    public function getFilteredUsers($dnis, WebinarEvent $webinarEvent)
    {
        $usersDnis = $webinarEvent->certifications->map(function ($certification) {
            return $certification->user->dni;
        })->toArray();

        $filteredDnis = array_diff($dnis, $usersDnis);

        return User::whereIn('dni', $filteredDnis)->where('user_type', null)->get();
    }

    public function getCertificationsArray($users, WebinarEvent $webinarEvent)
    {
        $certifications = [];
        $note = null;
        $status = null;

        foreach ($users as $i => $user) {

            $webinarEvent->loadCount('certifications');

            if ($webinarEvent->room->capacity > $webinarEvent->certifications_count) {

                $certifications[] = new WebinarCertification([
                    'user_id' => $user->id,
                    'observation' => NULL,
                    'unlock_cert' => 'N'
                ]);

                $status = 'finished';
            } else {
                $note = 'Se ha excedido la capacidad de la sala';
                $status = $i == 0 ? 'exceeded' : 'limitreached';
                break;
            }
        }

        return [$certifications, $status, $note];
    }

    public function destroy(WebinarCertification $webinarCertification)
    {
        return $webinarCertification->delete();
    }
}
