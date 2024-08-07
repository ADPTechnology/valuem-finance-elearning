<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserImportTemplate;
use App\Exports\UsersReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileImportRequest;
use App\Http\Requests\UserRequest;
use App\Imports\UsersImport;
use App\Models\File;
use App\Services\FileService;
use App\Services\ParticipantService;
use Illuminate\Http\Request;
use App\Models\{User};
use App\Services\UserService;
use Auth;
use Exception;

class AdminUsersController extends Controller
{
    private $userService;
    private $participantService;
    private $fileService;

    public function __construct(UserService $service, ParticipantService $participantService, FileService $fileService)
    {
        $this->userService = $service;
        $this->participantService = $participantService;
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->userService->getDataTable($request);
        } else {

            $roles = config('parameters')['roles'];

            return view('admin.users.index',
                compact(
                    'roles',
                )
            );
        }
    }

    public function registerValidateEmail(Request $request)
    {
        $valid = User::where('email', $request['email'])->first() == null ? "true" : "false";

        return $valid;
    }

    public function store(UserRequest $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->userService->store($request, $storage);
            $success = true;
            $message = config('parameters.stored_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function editValidateEmail(Request $request)
    {
        $id = $request['id'];
        $user = User::where('email', $request['email'])->first();

        return $user == null ||
            $user->id == $id ?
            'true' : 'false';
    }

    public function edit(User $user)
    {
        $user->loadAvatar();

        $role = config('parameters.roles')[$user->role] ?? '-';

        $isAuth = $user->id == Auth::user()->id;

        return response([
            "user" => $user,
            "role" => $role,
            "url_img" => verifyUserAvatar($user->file),
            "isAuth" => $isAuth,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->userService->update($request, $user, $storage);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function destroy(User $user)
    {
        $success = false;

        $user->progressChapters()->detach();

        if ($user->userDetail()) {
            $user->userDetail()->delete();
        }

        if ($user->delete()) {
            $success = true;
        }

        return response()->json([
            "success" => $success
        ]);
    }

    public function downloadImportTemplate()
    {
        $usersImportTemplate = new UserImportTemplate();

        return $usersImportTemplate->download('usuarios_plantilla_registro_masivo.xlsx');
    }

    public function massiveStore(FileImportRequest $request)
    {
        $note = NULL;
        $foundDuplicates = false;

        try {
            $usersImport = new UsersImport;
            $usersImport->import($request->file('file'));

            $success = true;
            $message = config('parameters.stored_message');

            if ($usersImport->failures()->isNotEmpty()) {
                $success = false;
                $message = 'Se encontró errores de validación';
            }

            if ($usersImport->getDuplicatedDnis()->isNotEmpty()) {
                $foundDuplicates = true;
                $note = 'Se encontraron DNIs duplicados';
                $notebody = $usersImport->getDuplicatedDnis();
            }

        } catch (Exception $e) {
            $success = false;
            $message = config('parameters.exception_message');
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            'foundDuplicates' => $foundDuplicates,
            'note' => $note,
            'notebody' => $notebody
        ]);
    }

    //-- EXPORT EXCEL ------

    public function exportExcel(Request $request)
    {
        $usersExport = new UsersReport;

        $users = User::with('company')->orderBy('id', 'desc')->limit(500)->get();

        $usersExport->setUsers($users);

        $date_info = 'últimos_500';

        return $usersExport->download(
            'reporte-usuarios_' . $date_info . '.xlsx'
        );
    }


    // * MY DOCUMENTS


    public function publishDocsForParticipant(Request $request, User $participant)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->participantService->storeParticipantFiles($request->file('files'), $participant, $storage);

            $participant->loadFilesParticipant();
            $files = $participant->files;

            $html = view('admin.users.partials.components._docs_list', compact('files', 'participant'))->render();


        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }

    public function getDocsContent(User $participant)
    {
        $participant->loadFilesParticipant();

        $files = $participant->files;

        $html = view('admin.users.partials.components._docs_list', compact('files', 'participant'))->render();

        return response()->json([
            'title' => $participant->full_name,
            'html' => $html
        ]);
    }

    public function destroyDocument(File $file, User $participant)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->fileService->destroy($file, $storage);
            $participant->loadFilesParticipant();
            $files = $participant->files;
            $html = view('admin.users.partials.components._docs_list', compact('files', 'participant'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }

    public function downloadDocument(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        if ($this->fileService->validateDownload($file, $storage)) {
            return $this->fileService->download($file, $storage);
        }

        return redirect()->route('admin.users.index')->with('flash_message', 'fileNotFound');
    }



    // INSTRUCTOR INFORMATION

    public function getInstructorInformation(User $instructor)
    {
        $instructor->userDetail()->firstOrCreate([]);

        $instructor->load('userDetail');

        $html = view('admin.users.partials.components._instructor_information', compact('instructor'))->render();

        return response()->json([
            "instructor" => $instructor,
            "html" => $html
        ]);
    }

    public function updateInstructorInformation(Request $request, User $instructor)
    {

        try {

            $data = $request->all();

            $newData = [
                'facebook_link' => $data['facebook_link'],
                'linkedin_link' => $data['linkedin_link'],
                'instagram_link' => $data['instagram_link'],
                'pag_web_link' => $data['pag_web_link'],
                'courses_count' => $data['courses_count'],
                'content' => $data['content'],
            ];

            $success = $instructor->userDetail()->update($newData);

            $instructor->load('userDetail');

            $html = view('admin.users.partials.components._instructor_information', compact('instructor'))->render();

            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "content" => $instructor->userDetail->content,
            'html' => $html,
            'data' => $data
        ]);
    }






}
