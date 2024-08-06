<?php

namespace App\Services;

use App\Http\Requests\UserExternalRequest;
use App\Models\{User};
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UserService
{
    public function getDataTable(Request $request)
    {
        $query = User::with('company:id,description')
            ->withCount(
                [
                    'events',
                    'certifications',
                    'publishings',
                    'userSurveys',
                    'participantGroups',
                    'webinarCertifications'
                ]
            );

        if ($request->filled('company')) {
            $query->where('company_id', $request['company']);
        }
        if ($request->filled('active')) {
            if ($request['active'] == 'S') {
                $query->where('active', 'S');
            } else if ($request['active'] == 'N') {
                $query->where('active', 'N');
            }
        }
        if ($request->filled('rol')) {
            $query->where('role', $request['rol']);
        }
        if ($request->filled('type')) {

            if ($request['type'] === 'internal') {
                $query->where('user_type', null);
            } else {
                $query->where('user_type', $request['type']);
            }
        }

        $allUsers = DataTables::of($query)
            ->addColumn('name', function ($user) {
                return $user->full_name;
            })
            ->editColumn('role', function ($user) {
                return config('parameters.roles')[$user->role] ?? '-';
            })
            ->editColumn('company.description', function ($user) {
                $company = $user->company->description ?? '-';

                return $company;
            })
            ->addColumn('status-btn', function ($user) {
                $status = $user->active == 'S' ? 'active' : 'inactive';
                $txtBtn = $status == 'active' ? 'Activo' : 'Inactivo';
                $statusBtn = '<span class="status ' . $status . '">' . $txtBtn . '</span>';

                return $statusBtn;
            })
            ->addColumn('action', function ($user) {

                $btn = '';

                if ($user->role == 'instructor') {
                    $btn .= '<button data-toggle="modal" data-id="' . $user->id . '"
                                            data-send="' . route('admin.instructor.information.edit', $user) . '"
                                            data-url="' . route('admin.instructor.information.update', $user) . '"
                                            data-original-title="edit" class="me-3 edit btn btn-dark btn-sm
                                            showInformationInstructor-btn"><i class="fa-solid fa-person-chalkboard"></i></button>';
                }

                if ($user->role == 'participants') {
                    $btn .= '<button data-toggle="modal" data-id="' . $user->id . '"
                                            data-send="' . route('admin.participant.getDocsContent', $user) . '"
                                            data-url="' . route('admin.participant.publishDocForParticipant', $user) . '"
                                            data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                                            showDocsParticipant"><i class="fa-solid fa-file-lines"></i></button>';
                }

                $btn .= '<button data-toggle="modal" data-id="' .
                    $user->id . '" data-url="' . route('admin.user.update', $user) . '"
                                data-send="' . route('admin.user.edit', $user) . '"
                                data-original-title="edit" class="edit btn btn-warning btn-sm
                                editUser"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $user->events_count == 0 &&
                    $user->certifications_count == 0 &&
                    $user->publishings_count == 0 &&
                    $user->user_surveys_count == 0 &&
                    $user->participant_groups_count == 0 &&
                    $user->webinar_certifications_count == 0 &&
                    $user->id != Auth::user()->id
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $user->id . '" data-original-title="delete"
                                    data-url="' . route('admin.user.delete', $user) . '" class="ms-3 edit btn btn-danger btn-sm
                                    deleteUser"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['status-btn', 'action'])
            ->make(true);

        return $allUsers;
    }

    public function store(Request $request, $storage)
    {
        $data = normalizeInputStatus($request->all());

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data + [
            "signature" => "N",
            "profile_survey" => "N"
        ]);

        if ($user) {

            $user->miningUnits()->sync($request['id_mining_units']);

            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'avatars';
                $belongsTo = 'avatars';
                $relation = 'one_one';

                $file = $request->file('image');

                return app(FileService::class)->store(
                    $user,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $user;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update(Request $request, User $user, $storage)
    {
        $data = normalizeInputStatus($request->all());

        $data['password'] = $data['password'] == NULL ? $user->password : Hash::make($data['password']);
        $data['role'] = $user->role == Auth::user()->role ? Auth::user()->role : $data['role'];
        $data['active'] = $user->id == Auth::user()->id ? 'S' : $data['active'];

        if ($user->update($data)) {

            $user->miningUnits()->sync($request['id_mining_units']);

            return $this->updateUserAvatar($request, $user, $storage);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function selfRegister(Request $request)
    {
        $data = $request->validated();

        $password = $this->generatePassword();

        $user = User::create($data + [
            "signature" => "N",
            "active" => "S",
            "profile_survey" => 'N',
            "role" => 'participants',
            "password" => Hash::make($password)
        ]);

        if ($user) {
            if ($user->miningUnits()->sync($request['mining_units_ids'])) {

                app(EmailService::class)->sendUserCredentialsMail($user, $password);

                return $user;
            };
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function externalSelfRegister(UserExternalRequest $request)
    {
        $data = $request->validated();

        $password = $request['password'];
        $data['password'] = Hash::make($password);

        $user = User::create($data + [
            'user_type' => 'external',
            "signature" => "N",
            "active" => "S",
            "role" => 'participants'
        ]);

        if ($user) {

            app(EmailService::class)->sendUserCredentialsMail($user, $password);
            return $user;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function generatePassword()
    {
        return Str::random(8);
    }

    public function updateUserAvatar(Request $request, User $user, $storage)
    {
        if ($request->hasFile('image')) {

            app(FileService::class)->destroy($user->file, $storage);

            $file_type = 'imagenes';
            $category = 'avatars';
            $file = $request->file('image');
            $belongsTo = 'avatars';
            $relation = 'one_one';

            return app(FileService::class)->store(
                $user,
                $file_type,
                $category,
                $file,
                $storage,
                $belongsTo,
                $relation
            );
        }

        return true;
    }

    public function updatePassword(Request $request, User $user)
    {
        if ($this->validateUpdatePasswordRequest($request)) {

            return $user->update([
                "password" => Hash::make($request['new_password'])
            ]);
        }

        return false;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     *
     */
    protected function validateUpdatePasswordRequest(Request $request)
    {
        return $request->validate([
            'old_password' => 'required|string|current_password',
            'new_password' => ['required', 'string'],
        ]);
    }





    // -------------- SIGNATURE ---------------

    public function storeSignature(User $user, $imgBase64, $storage)
    {
        $file_type = 'imagenes';
        $category = 'firmas';
        $belongsTo = 'firmas';

        if (
            app(FileService::class)->storeSignature(
                $user,
                $imgBase64,
                $file_type,
                $category,
                $belongsTo,
                $storage,
                null
            )
        ) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }





    // ---------- COMPANY ROL ------------------




    public function getUsersCompanyDataTable(string $companyDesc)
    {
        $query = User::whereHas('company', function ($query) use ($companyDesc) {
            $query->where('description', $companyDesc);
        })
            ->where('role', '!=', 'companies')
            ->with('company');

        $users = DataTables::of($query)
            ->editColumn('company.description', function ($user) {
                return $user->company->description ?? '-';
            })->editColumn('role', function ($user) {
                return config('parameters.roles')[$user->role] ?? '-';
            })
            ->addColumn('status-btn', function ($user) {
                return getStatusButton($user->active);
            })
            ->rawColumns(['status-btn'])
            ->make(true);

        return $users;
    }
}
