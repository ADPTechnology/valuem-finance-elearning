<?php

namespace App\Services\Home;

use App\Mail\FreeCourseRequest;
use App\Models\{Course, Event, User};
use App\Models\WebinarEvent;
use App\Services\Auth\AuthService;
use App\Services\CertificationService;
use Auth;
use Illuminate\Http\Request;
use Mail;

class HomeCertificationService
{
    public function userSelfRegisterCertification(Request $request, Event $event)
    {
        $user = Auth::user();

        if (app(AuthService::class)->validateUserCredentials($request)) {
            return app(CertificationService::class)->selfStore($user, $event);
        }

        return false;
    }

    public function userExternalSelfRegisterCertification(Request $request, WebinarEvent $event)
    {
        $user = Auth::user();

        if (app(AuthService::class)->validateUserCredentials($request)) {
            return app(CertificationService::class)->externalSelfStore($user, $event);
        }

        return false;
    }

    public function requestRegistrationCourse(Request $request, Course $course, User $user)
    {

        if (app(AuthService::class)->validateUserCredentials($request)) {
            $success = app(CertificationService::class)->requestRegistrationCourse($user, $course);

            if ($success) {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new FreeCourseRequest($user, $course, $admin));
                }
            }

            return $success;
        }

        return false;
    }
}
