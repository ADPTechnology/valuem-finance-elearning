<?php

namespace App\Http\Controllers\Aula\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\File;
use App\Services\{CompanyService, FileService};
use Illuminate\Http\Request;
use Auth;
use Exception;

class AulaDocCompanyController extends Controller
{
    private $companyService;
    private $fileService;

    public function __construct(CompanyService $service, FileService $fileService)
    {
        $this->companyService = $service;
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $company = Auth::user()->company;
            return $this->companyService->getDocumentsDataTable($company);
        }

        return view('aula.company.docCompany.index');
    }

    public function storeFile(Request $request)
    {
        $user = Auth::user();
        $storage = env('FILESYSTEM_DRIVER');
        $company = $user->company;

        try {
            $success = $this->companyService->storeCompanyFiles($request->file('files'), $company, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        if ($this->fileService->validateDownload($file, $storage)) {
            return $this->fileService->download($file, $storage);
        }

        return redirect()->route('aula.docCompany.index')->with('flash_message', 'fileNotFound');
    }

    public function destroyFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->fileService->destroy($file, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }
}
