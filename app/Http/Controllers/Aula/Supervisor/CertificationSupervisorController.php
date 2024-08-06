<?php

namespace App\Http\Controllers\Aula\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CertificationService;
use Exception;
use Illuminate\Http\Request;

class CertificationSupervisorController extends Controller
{

    protected $cetificationService;

    public function __construct(CertificationService $service)
    {
        $this->cetificationService = $service;
    }

    public function index()
    {
        return view('aula.supervisor.index');
    }

    public function getCertificationDocuments(Request $request)
    {
        $html = '';

        try {
            if ($request->filled('dni')) {

                $course_types_collection = $this->cetificationService->getByFilters($request)->sortKeys();
                $user = User::where('dni', $request['dni'])->first();

                $html = view('aula.supervisor.components._view_documents', compact('user', 'course_types_collection'))->render();

                $success = true;

            } else {
                $success = false;
            }
        } catch (Exception $th) {
            $succes = false;
        }

        return response()->json([
            'html' => $html,
            'success' => $success
        ]);

    }

}
