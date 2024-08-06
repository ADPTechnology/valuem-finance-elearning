<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use App\Models\{CourseModule, SpecCourse};
use App\Services\SpecCourseService;
use Auth;

class AulaSpecCourseController extends Controller
{
    private $specCourseService;

    public function __construct(SpecCourseService $service)
    {
        $this->specCourseService = $service;
    }

    public function index()
    {
        $user = Auth::user();

        $specCourses = $this->specCourseService->getSpecCourses();

        return view('aula.common.specCourses.index', compact(
            'specCourses'
        ));
    }

    public function show(SpecCourse $specCourse)
    {
        $user = Auth::user();

        if ($user->role == 'instructor') {
            $specCourse->load(
                [
                    'groupEvents' => fn ($q) =>
                    $q->whereHas('events', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                ]);
        }

        return view('aula.common.specCourses.show', compact(
            'specCourse'
        ));
    }

    public function getModuleFiles(CourseModule $module)
    {
        $files = $module->files()->get();

        $html = view('aula.common.specCourses.modules.partials.components._files_list', compact(
            'files'
        ))->render();

        return response()->json([
            'html' => $html
        ]);
    }
}
