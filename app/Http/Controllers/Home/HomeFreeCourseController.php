<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Services\CourseCategoryService;
use App\Services\FreeCourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeFreeCourseController extends Controller
{
    public function index()
    {
        $categories = app(CourseCategoryService::class)->withCategoryRelationshipsQuery()
            ->where('status', 'S')
            ->get();

        return view('home.freecourses.index', compact(
            'categories'
        ));
    }

    public function show(CourseCategory $category)
    {
        $freeCourses = app(FreeCourseService::class)->withFreeCourseRelationshipsQuery()
            ->where('active', 'S')
            ->where('category_id', $category->id)
            ->having('course_chapters_count', '>', 0)
            ->get();

        return view('home.freecourses.show', compact(
            'freeCourses',
            'category'
        ));
    }


    public function getInformation(Course $freeCourse)
    {

        $freeCourse->load([
            'freecourseDetail',
            'file' => fn ($query) =>
            $query->where('file_type', 'imagenes')
                ->where('category', 'cursoslibres'),
        ]);

        $html = view('home.common.partials.boxes._information_free_courses_box', compact('freeCourse'))->render();

        return response()->json([
            'freeCourse' => $freeCourse,
            'html' => $html
        ]);
    }
}
