<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Services\FolderService;
use App\Models\{Course, File};


class AulaFreeCourseLiveResourcesController extends Controller
{

    protected $folderService;

    public function __construct(FolderService $serviceFolder)
    {
        $this->folderService = $serviceFolder;
    }

    public function index(Course $course)
    {
        $filesCourse = $course->files()->where('file_type', 'archivos')->get();

        return view('aula.common.live-free-courses.files.index', compact(
            'course',
            'filesCourse'
        )
        );
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        return $this->folderService->downloadFile($file, $storage);
    }

}
