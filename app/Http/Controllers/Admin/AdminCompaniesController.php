<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CompaniesReport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Company, File};
use App\Services\{CompanyService, FileService};
use Exception;

class AdminCompaniesController extends Controller
{
    private $companyService;

    public function __construct(CompanyService $service)
    {
        $this->companyService = $service;
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            return $this->companyService->getDataTable();
        }
        return view('admin.companies.index');
    }

    public function store(Request $request)
    {
        if($request['type'] == 'validate')
        {
            $valid = Company::where('ruc', $request['ruc'])->first() == null ? "true" : "false";

            return $valid;
        }
        elseif($request['type'] == 'store')
        {
            parse_str($request['data'], $data);

            $status = 'N';

            if(array_key_exists('companyStatusCheckbox', $data))
            {
                $status = $data['companyStatusCheckbox'] == 'on' ? 'S' : 'N';
            }

            Company::create([
                "description" => $data['name'],
                "abbreviation" => $data['abreviation'],
                "rubric" => $data['rubric'],
                "ruc" => $data['ruc'],
                "address" => $data['address'],
                "telephone" => $data['phone'],
                "name_ref" => $data['referName'],
                "telephone_ref" => $data['referPhone'],
                "email_ref" => $data['referEmail'],
                "active" => $status
            ]);

            return response()->json([
                "success" => "store successfully"
            ]);
        }
    }

    public function edit(Company $company)
    {
        return response()->json($company);
    }

    public function EditvalidateRuc(Request $request)
    {
        $id = $request['id'];
        $company = Company::where('ruc', $request['ruc'])->first();

        return  $company == null ||
                $company->id == $id ? 'true' : 'false';
    }

    public function update(Request $request, Company $company)
    {
        $status = $request['companyStatusCheckbox'] == 'on' ? 'S' : 'N';

        $company->update([
            "description" => $request['name'],
            "abbreviation" => $request['abreviation'],
            "rubric" => $request['rubric'],
            "ruc" => $request['ruc'],
            "address" => $request['address'],
            "telephone" => $request['phone'],
            "name_ref" => $request['referName'],
            "telephone_ref" => $request['referPhone'],
            "email_ref" => $request['referEmail'],
            "active" => $status
        ]);

        return response()->json([
            "success" => "updated successfully"
        ]);
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            "success" => true
        ]);
    }

    public function publishDocsInCompany(Company $company, Request $request)
    {
        $storage = env('FILESYSTEM_DRIVER');


        try {
            $success = $this->companyService->storeCompanyFiles($request->file('files'), $company, $storage);

            $company->loadFiles();
            $files = $company->files;
            $html = view('admin.companies.partials.components._docs_list', compact('files', 'company'))->render();


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

    public function destroyDocument(File $file, Company $company)
    {

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = app(FileService::class)->destroy($file, $storage);
            $files = $company->files;
            $html = view('admin.companies.partials.components._docs_list', compact('files', 'company'))->render();

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

    //-- EXPORT EXCEL ------

    public function exportExcel(Request $request)
    {
        $companiesExport = new CompaniesReport;

        $companies = Company::orderBy('id', 'desc')->limit(500)->get();

        $companiesExport->setCompanies($companies);

        $date_info = 'últimos_500';

        return $companiesExport->download(
            'reporte-empresas_'. $date_info .'.xlsx'
        );
    }


    public function getDocsContent(Company $company)
    {
        $company->loadFiles();

        $files = $company->files;

        $html = view('admin.companies.partials.components._docs_list', compact('files', 'company'))->render();

        return response()->json([
            'title' => $company->description,
            'html' => $html
        ]);
    }

    public function downloadDocument(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        if (app(FileService::class)->validateDownload($file, $storage)) {
            return app(FileService::class)->download($file, $storage);
        }

        return redirect()->route('admin.companies.index')->with('flash_message', 'fileNotFound');
    }
}
