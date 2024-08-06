<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WebinarAllReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\{WebinarRequest};
use App\Models\{Webinar, File};
use App\Services\{FileService, WebinarService};
use Exception;
use Illuminate\Http\Request;

class WebinarController extends Controller
{
    private $webinarService;

    public function __construct(WebinarService $service)
    {
        $this->webinarService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->webinarService->getDataTable();
        }

        return view('admin.webinars.index');
    }

    public function show(Webinar $webinar)
    {
        $webinar->loadImage();
        $webinar->loadCount([
            'files' => function ($q) {
                $q->where('file_type', 'archivos');
            },
            'events'
        ]);

        return view('admin.webinars.show',
            compact(
                'webinar'
            )
        );
    }

    public function store(WebinarRequest $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $webinar = $this->webinarService->store($request, $storage);
            $success = $webinar ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        $route = $request['verifybtn'] == 'show' ? route('admin.webinars.all.show', $webinar) : null;
        $show = $route ? true : false;

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => $route,
            "show" => $show,
        ]);
    }

    public function edit(Webinar $webinar)
    {
        $webinar->loadImage();

        return response()->json([
            "webinar" => $webinar,
            "image" => verifyImage($webinar->file)
        ]);
    }

    public function update(WebinarRequest $request, Webinar $webinar)
    {
        $storage = env('FILESYSTEM_DRIVER');
        $webinar->loadImage();

        $html = null;
        $show = false;

        try {
            $webinar = $this->webinarService->update($request, $webinar, $storage);
            $success = $webinar ? true : false;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($request['place'] == 'show') {
            $webinar->loadCount([
                'files' => function ($q) {
                    $q->where('file_type', 'archivos');
                },
                'events'
            ]);
            $webinar->loadImage();
            $html = view('admin.webinars.partials.components._webinar_box', compact('webinar'))->render();
            $show = true;
        }

        return response()->json([
            "message" => $message,
            "success" => $success,
            "html" => $html,
            "show" => $show,
            'title' => mb_strtolower($webinar->title, 'UTF-8'),
        ]);
    }

    public function destroy(Request $request, Webinar $webinar)
    {
        $webinar->loadImage();
        $storage = env('FILESYSTEM_DRIVER');

        $show = false;
        $route = null;

        try {
            $success = $this->webinarService->destroy($webinar, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        if ($request['place'] == 'show') {
            $route = route('admin.webinars.all.index');
            $show = true;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'route' => $route,
            'show' => $show,
        ]);
    }




    // ------------ FILES --------------


    public function getFilesDataTable(Webinar $webinar)
    {
        return $this->webinarService->getFilesDataTable($webinar);
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');
        return app(FileService::class)->download($file, $storage);
    }

    public function destroyFile(Webinar $webinar, File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = app(FileService::class)->destroy($file, $storage);
        $message = getMessageFromSuccess($success, 'deleted');

        $webinar->loadCount([
            'files' => function ($q) {
                $q->where('file_type', 'archivos');
            },
            'events'
        ]);
        $webinar->loadImage();
        $html = view('admin.webinars.partials.components._webinar_box', compact('webinar'))->render();

        return response()->json([
            "success" => true,
            "message" => $message,
            "html" => $html
        ]);
    }

    public function storeFiles(Request $request, Webinar $webinar)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->webinarService->storeFiles($request, $webinar, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        $webinar->loadCount([
            'files' => function ($q) {
                $q->where('file_type', 'archivos');
            },
            'events'
        ]);
        $webinar->loadImage();
        $html = view('admin.webinars.partials.components._webinar_box', compact('webinar'))->render();

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }


    // EXPORT EXCEL

    public function exportExcel(Request $request)
    {
        $webinarsExport = new WebinarAllReport;

        $webinars = Webinar::orderBy('id', 'desc')->limit(500)->get();

        $webinarsExport->setWebinars($webinars);

        $date_info = 'últimos_500';

        return $webinarsExport->download(
            'reporte-webinars_' . $date_info . '.xlsx'
        );
    }
}
