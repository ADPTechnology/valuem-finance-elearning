<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\File;
use App\Services\AssignmentService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{

    protected $assigmentService;

    public function __construct(AssignmentService $assigmentService)
    {
        $this->assigmentService = $assigmentService;
    }


    public function getDatatable(Event $event)
    {
        return $this->assigmentService->getDatatable($event);
    }


    public function store(Request $request, Event $event)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->assigmentService->store($request, $event, $storage);
            $success = true;

        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);

    }

    public function edit(Assignment $assignment)
    {
        $assignment->load('participantGroups');
        return $assignment;
    }

    public function update(Request $request, Assignment $assignment)
    {

        try {

            $this->assigmentService->update($request, $assignment);
            $success = true;

        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);

    }


    public function destroy(Assignment $assignment)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {

            $this->assigmentService->destroy($assignment, $storage);
            $success = true;

        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);

    }

    public function getDocuments(Assignment $assignment)
    {
        $assignment->loadFiles();

        $files = $assignment->files;

        $html = view('admin.events.partials.modals._load_docs_assigments', compact('files', 'assignment'))->render();

        return response()->json([
            'title' => $assignment->title,
            'html' => $html
        ]);
    }


    public function uploadDocuments(Assignment $assignment, Request $request)
    {
        $storage = env('FILESYSTEM_DRIVER');


        try {
            $success = $this->assigmentService->uploadDocuments($assignment, $request, $storage);

            $assignment->loadFiles();
            $files = $assignment->files;

            $html = view('admin.events.partials.modals._load_docs_assigments', compact('files', 'assignment'))->render();

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


    public function dowloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');
        return app(FileService::class)->download($file, $storage);
    }

    public function destroyFile(File $file, Assignment $assignment)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {

            $success = $this->assigmentService->destroyFile($file, $storage);

            $assignment->loadFiles();
            $files = $assignment->files;

            $html = view('admin.events.partials.modals._load_docs_assigments', compact('files', 'assignment'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            "success" => $success,
            "html" => $html,
            'title' => $assignment->title,
            "message" => $message,
        ]);
    }


}
