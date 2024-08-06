<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use Auth;
use Exception;

class AulaProfileController extends Controller
{
    private $userService;
    private $newService;

    public function __construct(UserService $service, NewsService $newsService)
    {
        $this->userService = $service;
        $this->newService = $newsService;
    }

    public function index()
    {
        $user = Auth::user();

        $news = $this->newService->getNews(true);

        return view('aula.common.profile.index', [
            'user' => $user,
            'news' => $news
        ]);
    }

    public function editUserAvatar(User $user)
    {
        $user->loadAvatar();
        $url_img = verifyUserAvatar($user->file);

        return response()->json([
            "url_img" => $url_img
        ]);
    }

    public function updateUserAvatar(Request $request, User $user)
    {
        $user->loadAvatar();

        $storage = env('FILESYSTEM_DRIVER');
        $htmlAvatar = NULL;
        $htmlAside = NULL;

        try {
            $success = $this->userService->updateUserAvatar($request, $user, $storage);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $htmlSidebarAvatar = view('aula.common.partials.boxes._sidebar_profile_image')->render();
            $htmlAvatar = view('aula.common.profile.partials.boxes._profile_image', compact('user'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "htmlAvatar" => $htmlAvatar,
            "htmlSidebarAvatar" => $htmlSidebarAvatar
        ]);
    }

    public function updatePassword(Request $request, User $user)
    {
        $htmlForm = NULL;

        try {
            $success = $this->userService->updatePassword($request, $user);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $htmlForm = view('aula.common.profile.partials.boxes._form_update_password')->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "htmlForm" => $htmlForm
        ]);
    }


    // INFORMATION INSTRUCTOR

    public function getInformation()
    {
        $instructor = Auth::user();

        $instructor->loadAvatar();

        $instructor->userDetail()->firstOrCreate([]);

        $instructor->load('userDetail');

        return view('aula.common.profile.information', [
            'instructor' => $instructor
        ]);
    }

    public function editInformation()
    {

        $instructor = Auth::user();

        $instructor->load('userDetail');

        $html = view('aula.common.profile.partials.components._instructor_information', compact('instructor'))->render();

        return response()->json([
            "html" => $html,
            "content" => $instructor->userDetail->content
        ]);
    }

    public function updateInformation(Request $request)
    {
        $instructor = Auth::user();

        $instructor->load('userDetail');

        $data = $request->all();

        $newData = [
            'facebook_link' => $data['facebook_link'],
            'linkedin_link' => $data['linkedin_link'],
            'instagram_link' => $data['instagram_link'],
            'pag_web_link' => $data['pag_web_link'],
            'courses_count' => $data['courses_count'] ?? 0,
            'content' => $data['content'],
        ];

        $success = $instructor->userDetail()->update($newData);

        $instructor->load('userDetail');
        $html = view('aula.common.profile.partials.boxes._profile_information', compact('instructor'))->render();

        $message = config('parameters.updated_message');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);

    }



}
