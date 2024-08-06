<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\{Company, Course, MiningUnit, Publishing, User};
use App\Services\Home\{HomeCourseService};
use App\Services\{CourseCategoryService};
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $courseCategoryService;
    protected $homeCourseService;

    public function __construct(CourseCategoryService $courseCategoryService, HomeCourseService $homeCourseService)
    {
        $this->courseCategoryService = $courseCategoryService;
        $this->homeCourseService = $homeCourseService;
    }

    public function index(Request $request)
    {
        $courses = $this->homeCourseService->getAvailableCourses();
        $instructors = User::where('role', 'instructor')
            ->where('active', 'S')
            ->with([
                'file' => fn($q) =>
                    $q->where('category', 'avatars')
            ])->get();

        $categories = $this->courseCategoryService->withCategoryRelationshipsQuery()
            ->where('status', 'S')
            ->get();

        $banners = Publishing::where('type', 'PRINCIPALBANNER')
                                ->with('file')
                                ->orderBy('publishing_order', 'ASC')
                                ->get();

        $numberUsers = User::where('role', 'participants')->count();
        $numberCourses = Course::count();
        $numberCompanys = Company::count();


        return view('home.home',
            compact(
                'courses',
                'instructors',
                'categories',
                'numberUsers',
                'numberCourses',
                'numberCompanys',
                'banners'
            )
        );
    }

    public function getRegisterModalContent($place)
    {

        $miningUnits = MiningUnit::get(['id', 'description']);
        $companies = Company::where('active', 'S')->get(['id', 'description']);

        if ($place == 'external') {

            return response()->json([
                "html" => view('home.common.partials.boxes._login_register_external')->render()
            ]);
        }

        return response()->json([
            "html" => view(
                'home.common.partials.boxes._login_register',
                compact(
                    'miningUnits',
                    'companies'
                )
            )->render()
        ]);

    }
}
