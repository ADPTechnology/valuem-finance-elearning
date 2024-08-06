<?php

namespace App\Http\Controllers\Aula\Company;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Auth;
class AulaUserCompanyController extends Controller
{
    private $usersService;

    public function __construct(UserService $service)
    {
        $this->usersService = $service;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            return $this->usersService->getUsersCompanyDataTable($user->company->description);
        }

        return view('aula.company.userCompany.index');
    }
}
