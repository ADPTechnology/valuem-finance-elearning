<?php

namespace App\Http\Controllers\Common\Certificate;

use App\Http\Controllers\Controller;
use App\Models\{User};
use App\Services\CertificationService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    private $certificationService;

    public function __construct(CertificationService $service)
    {
        $this->certificationService = $service;
    }

    public function index(Request $request)
    {
        $user = null;
        $course_types_collection = null;

        if ($request->filled('dni')) {
            $user = User::where('dni', $request['dni'])->first();
            $course_types_collection = $this->certificationService->getByFilters($request)->sortKeys();
        }

        return view('certificates.index', compact(
            'user',
            'course_types_collection'
        ));
    }
}
