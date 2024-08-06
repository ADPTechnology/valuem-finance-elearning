<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\{Webinar, File};
use App\Services\FolderService;
use Illuminate\Http\Request;

class AulaWebinarResourcesController extends Controller
{
    protected $folderService;

    public function __construct(FolderService $serviceFolder)
    {
        $this->folderService = $serviceFolder;
    }

    public function index(Webinar $webinar)
    {
        $filesCourse = $webinar->files()->where('file_type', 'archivos')->get();

        return view('aula.common.webinars.files.index', compact('webinar', 'filesCourse'));
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        return $this->folderService->downloadFile($file, $storage);
    }
}
