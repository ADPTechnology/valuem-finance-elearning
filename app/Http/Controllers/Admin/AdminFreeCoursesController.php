<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FreeCourseRequest;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;

use App\Models\{Company, CourseCategory, Course};
use App\Services\{CourseCategoryService, FreeCourseService};
use Exception;

class AdminFreeCoursesController extends Controller
{
    private $freeCourseService;
    private $fileService;

    public function __construct(FreeCourseService $service, FileService $fileService)
    {
        $this->freeCourseService = $service;
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->freeCourseService->getCoursesDataTable();
        }

        $categories = app(CourseCategoryService::class)->withCategoryRelationshipsQuery()->get();

        return view('admin.free-courses.index', [
            'categories' => $categories
        ]);
    }

    public function getCategoriesRegisterCourse()
    {
        return response()->json([
            "categories" => CourseCategory::get(['id', 'description'])
        ]);
    }


    public function store(FreeCourseRequest $request)
    {
        $success = true;
        $message = null;
        $html = null;
        $route = null;
        $show = false;

        $category_id = $request['category_id'];
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $course = $this->freeCourseService->store($request, $storage);
            $message = config('parameters.stored_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($request['verifybtn'] == 'show') {
            $route = route('admin.freeCourses.courses.index', $course);
            $show = true;
        } else {
            if ($request->has('fixedCategory')) {
                $category = CourseCategory::where('id', $category_id)->with('courses')->first();
                $html = view('admin.free-courses.partials.category-box', compact('category'))->render();
            } else {
                $categories = CourseCategory::with('courses')->get();
                $html = view('admin.free-courses.partials.categories-list', compact('categories'))->render();
            }
        }

        return response()->json([
            "show" => $show,
            "success" => $success,
            "message" => $message,
            "html" => $html,
            "route" => $route,
        ]);
    }

    public function show(Course $course)
    {
        $course->loadFreeCourseRelationships();

        $sectionActive = '';

        $companies = Company::get(['id', 'description']);

        return view('admin.free-courses.courses.index', [
            'course' => $course,
            'companies' => $companies,
            'sectionActive' => $sectionActive
        ]);
    }

    public function edit(Course $course)
    {
        $course->loadFreeCourseImage();
        $course->load('freecourseDetail');

        $url_img = verifyImage($course->file);

        return response()->json([
            "description" => $course->description,
            "subtitle" => $course->subtitle,
            "hours" => $course->hours,
            "status" => $course->active,
            "min_score" => $course->min_score,
            "recom" => $course->flg_recom,
            "price" => $course->freecourseDetail->price,
            "description_details" => $course->freecourseDetail->description,
            "url_img" => $url_img
        ]);
    }

    public function update(FreeCourseRequest $request, Course $course)
    {
        $course->loadFreeCourseImage();

        $success = true;
        $message = null;
        $html = null;
        $description = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->freeCourseService->update($request, $storage, $course);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $course->loadFreeCourseRelationships();
            $html = view('admin.free-courses.partials.course-box', compact('course'))->render();
            $description = mb_strtolower($course->description, 'UTF-8');
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "description" => $description,
            "html" => $html
        ]);
    }

    public function destroy(Course $course)
    {
        $course->loadFreeCourseRelationships();

        $success = true;
        $message = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->freeCourseService->destroy($storage, $course);
            $message = config('parameters.deleted_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        $category = $course->courseCategory;

        return response()->json([
            "success" => $success,
            "message" => $message,
            "route" => route('admin.freeCourses.categories.index', $category)
        ]);
    }


    // SELECT

    public function getExamsThatBelongToCourse(Course $course, Request $request)
    {

        $exams = $course->exams()
            ->doesntHave('fcEvaluations')
            ->where('active', 'S')
            ->get(['id', 'title']);

        return response()->json($exams);
    }


    // FILES


    public function getFilesDataTable(Course $course)
    {
        return $this->freeCourseService->getFilesDataTable($course);
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');
        return $this->fileService->download($file, $storage);
    }

    public function storeFiles(Request $request, Course $course)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->freeCourseService->storeFiles($request, $course, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }

    public function destroyFile(Course $course, File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->fileService->destroy($file, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => true,
            "message" => $message,
        ]);
    }
}
