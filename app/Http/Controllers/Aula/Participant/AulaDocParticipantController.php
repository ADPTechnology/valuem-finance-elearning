<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\FileService;
use App\Services\ParticipantService;
use Exception;
use Auth;
use Illuminate\Http\Request;

class AulaDocParticipantController extends Controller
{
    private $participantService;
    private $fileService;

    public function __construct(ParticipantService $service, FileService $fileService)
    {
        $this->participantService = $service;
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->participantService->getDocumentsDataTable();
        }

        return view('aula.viewParticipant.docs.index');
    }

    public function storeFile(Request $request)
    {
        $storage = env('FILESYSTEM_DRIVER');
        $user = Auth::user();


        try {
            $success = $this->participantService->storeParticipantFiles($request->file('files'), $user, $storage);
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

        return redirect()->route('aula.myDocs.index')->with('flash_message', 'fileNotFound');
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
