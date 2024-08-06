<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{FileRequest};
use App\Models\File;
use App\Services\{FileService};
use Exception;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $fileService;

    public function __construct(FileService $service)
    {
        $this->fileService = $service;
    }

    public function index(Request $request) 
    {
        if ($request->ajax()) {
            return $this->fileService->getDatatable($request);
        }

        return view('admin.filesManagement.index');
    }

    public function storeFile(Request $request) 
    {
        $storage = env('FILESYSTEM_DRIVER');
        $rejected = NULL;
        $found_errors = false;

        try {
            list($success, $rejected, $found_errors) = $this->fileService->storeFile($request->file('files'), $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "rejected" => $rejected,
            "foundErrors" => $found_errors
        ]);
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        if ($this->fileService->validateDownload($file, $storage)) {
            return $this->fileService->download($file, $storage);
        }

        return redirect()->route('admin.filesManagement.index')->with('flash_message', 'fileNotFound');
     
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
