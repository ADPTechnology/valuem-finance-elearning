<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ForgettingCurveReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgettingCurveRequest;
use App\Models\FcInstance;
use App\Models\ForgettingCurve;
use App\Services\CurveStepService;
use Exception;
use Illuminate\Http\Request;
use App\Services\ForgettingCurveService;
use App\Models\Course;
use App\Models\CourseType;

class ForgettingCurveController extends Controller
{
    protected $forgettingCurveService;
    protected $stepCurveService;
    public function __construct(ForgettingCurveService $service, CurveStepService $stepService)
    {
        $this->forgettingCurveService = $service;
        $this->stepCurveService = $stepService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->forgettingCurveService->getDataTable();
        }

        return view('admin.forgettingCurve.index');
    }

    public function show(ForgettingCurve $forgettingCurve)
    {

        $instanceActive = '';
        $forgettingCurve->loadRelationShips();

        $fc_step_progress_count = 0;
        $instances = $forgettingCurve->instances;

        $fc_step_progress_count += $instances->sum(function ($instance) {
            return $instance->steps->sum(function ($step) {
                return $step->fc_step_progress_count;
            });
        });

        $fc_exams_count = 0;

        $fc_exams_count = $instances->sum(function ($instance) {
            return $instance->steps->sum(function ($step) {
                return $step->exams_count;
            });
        });

        $f_videos_count = 0;

        $f_videos_count = $instances->sum(function ($instance) {
            return $instance->steps->sum(function ($step) {
                return $step->video_count;
            });
        });

        $f_questions_count = 0;

        $f_questions_count = $instances->sum(function ($instance) {
            return $instance->steps->sum(function ($step) {
                return $step->exams->sum(function ($exam) {
                    return $exam->questions_count;
                });

            });
        });

        $f_questions_video_count = 0;

        $f_questions_video_count = $instances->sum(function ($instance) {
            return $instance->steps->sum(function ($step) {
                return $step->video ? $step->video->questions_count : 0;
            });
        });

        return view('admin.forgettingCurve.show', compact('forgettingCurve', 'instanceActive', 'fc_step_progress_count', 'fc_exams_count', 'f_videos_count', 'f_questions_count', 'f_questions_video_count'));
    }

    public function getCourses(Request $request)
    {
        if ($request['column'] == 'type') {
            $courses = CourseType::get();
        } else if ($request['column'] == 'course') {

            if ($type_id = $request['type_id']) {
                $courses = Course::where('course_type_id', $type_id)
                    ->doesntHave('forgettingCurves')
                    ->get(['id', 'course_type_id', 'description']);
            } else {
                $courses = collect();
            }
        }

        return response()->json($courses);
    }

    public function store(ForgettingCurveRequest $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->forgettingCurveService->store($request, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function edit(ForgettingCurve $forgettingCurve)
    {
        $forgettingCurve->load('courses');

        return response()->json([
            'success' => true,
            'data' => $forgettingCurve,
            'image' => verifyImage($forgettingCurve->file)
        ]);
    }

    public function update(ForgettingCurveRequest $request, ForgettingCurve $forgettingCurve)
    {
        $storage = env('FILESYSTEM_DRIVER');
        $forgettingCurve->loadImage();

        $html = null;
        $show = false;

        $isEdit = $request['place'];

        try {

            $success = $this->forgettingCurveService->update($request, $forgettingCurve, $storage);
        } catch (Exception $e) {

            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($isEdit == 'edit') {

            $forgettingCurve->loadRelationShips();
            $forgettingCurve->loadImage();

            $fc_step_progress_count = 0;
            $instances = $forgettingCurve->instances;

            $fc_step_progress_count += $instances->sum(function ($instance) {
                return $instance->steps->sum(function ($step) {
                    return $step->fc_step_progress_count;
                });
            });

            $fc_exams_count = 0;

            $fc_exams_count = $instances->sum(function ($instance) {
                return $instance->steps->sum(function ($step) {
                    return $step->exams_count;
                });
            });

            $f_videos_count = 0;

            $f_videos_count = $instances->sum(function ($instance) {
                return $instance->steps->sum(function ($step) {
                    return $step->video_count;
                });
            });

            $f_questions_count = 0;

            $f_questions_count = $instances->sum(function ($instance) {
                return $instance->steps->sum(function ($step) {
                    return $step->exams->sum(function ($exam) {
                        return $exam->questions_count;
                    });

                });
            });

            $f_questions_video_count = 0;

            $f_questions_video_count = $instances->sum(function ($instance) {
                return $instance->steps->sum(function ($step) {
                    return $step->video ? $step->video->questions_count : 0;
                });
            });

            $html = view('admin.forgettingCurve.partials.components._forgettingCurve_box', compact('forgettingCurve', 'fc_step_progress_count', 'fc_step_progress_count', 'fc_exams_count', 'f_videos_count', 'f_questions_count', 'f_questions_video_count'))->render();
            $show = true;
        }

        return response()->json([
            'isEdit' => $isEdit,
            'success' => $success,
            'message' => $message,
            'html' => $html,
            'show' => $show,
            'title' => mb_strtolower($forgettingCurve->title, 'UTF-8')
        ]);
    }

    public function destroy(Request $request, ForgettingCurve $forgettingCurve)
    {
        $forgettingCurve->loadImage();
        $storage = env('FILESYSTEM_DRIVER');

        $route = '';

        try {

            $success = $this->forgettingCurveService->destroy($forgettingCurve, $storage);

        } catch (Exception $e) {

            $success = false;
        }

        if ($request->has('place')) {
            $route = route('admin.forgettingCurve.index');
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'route' => $route
        ]);
    }

    public function getDatatableSteps(Request $request, FcInstance $instance)
    {
        if ($request['type'] == 'html') {

            $html = view('admin.forgettingCurve.partials.components._group_steps_list')->render();

            return response()->json([
                'html' => $html,
                'title' => $instance->title,
            ]);
        } else if ($request['type'] == 'table') {

            return $this->stepCurveService->getDatatable($instance);
        }
    }


    // EXPORT EXCEL

    public function exportExcel(Request $request)
    {
        $forgettingCurveExport = new ForgettingCurveReport;

        $forgettingCurves = ForgettingCurve::with([
            'courses' => function ($q) {
                $q->select('courses.id', 'description', 'course_type_id');
            },
            'courses.type'
        ])->orderBy('id', 'desc')->limit(500)->get();

        $forgettingCurveExport->setForgettingCurves($forgettingCurves);

        $date_info = 'últimos_500';

        return $forgettingCurveExport->download(
            'reporte-curvas-del-olvido_' . $date_info . '.xlsx'
        );
    }
}
