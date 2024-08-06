<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\{SpecCourse, File};
use App\Services\FolderService;
use App\Services\SpecFileService;
use Illuminate\Http\Request;

class AulaSpecFileController extends Controller
{
    protected $folderService;

    public function __construct(FolderService $serviceFolder)
    {
        $this->folderService = $serviceFolder;
    }

    public function index(SpecCourse $specCourse)
    {
        $filesCourse = $specCourse->files()->where('file_type', 'archivos')->get();

        return view('aula.common.specCourses.files.index', compact(
            'specCourse',
            'filesCourse'
        ));
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        return $this->folderService->downloadFile($file, $storage);
    }

}
