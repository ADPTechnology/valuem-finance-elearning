<?php

namespace App\Services;

use App\Models\ForgettingCurve;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\CurveInstanceService;
use App\Services\CurveStepService;
use Exception;

class ForgettingCurveService
{

    protected $instanceService;
    protected $stepService;
    protected $fileService;


    public function __construct(CurveInstanceService $instanceService, CurveStepService $stepService, FileService $fileService)
    {
        $this->instanceService = $instanceService;
        $this->stepService = $stepService;
        $this->fileService = $fileService;
    }

    public function getDataTable()
    {

        $query = ForgettingCurve::with([
            'courses.type',
            'instances' => function ($q) {
                $q->select('id', 'title', 'days_count', 'forgetting_curve_id')
                    ->with([
                        'steps' => function ($q) {
                            $q->withCount(['fcStepProgress', 'exams', 'video']);
                            $q->with([
                                'video' => function ($q1) {
                                    $q1->withCount('questions');
                                },
                                'exams' => function ($q) {
                                    $q->withCount('questions');
                                }
                            ]);
                        }
                    ]);
            }
        ])
            ->select('id', 'title', 'description', 'min_score', 'active');

        return Datatables::of($query)
            ->addColumn('title', function ($forgettingCurve) {
                return '<a href="' . route('admin.forgettingCurve.show', $forgettingCurve) . '">' . $forgettingCurve->title . '</a>';
            })
            ->addColumn('description', function ($forgettingCurve) {

                return $forgettingCurve->description ?? '-';
            })
            ->addColumn('min_score', function ($forgettingCurve) {
                return $forgettingCurve->min_score;
            })
            ->addColumn('active', function ($forgettingCurve) {

                $status = $forgettingCurve->active;
                $statusBtn = '<span class="status ' . getStatusClass($status) . '">' . getStatusText($status) . '</span>';

                return $statusBtn;

            })
            ->addColumn('course_type', function ($forgettingCurve) {
                return $forgettingCurve->courses->first()->type->name ?? '-';
            })
            ->addColumn('course_name', function ($forgettingCurve) {
                $list = '<ul>';
                foreach ($forgettingCurve->courses as $course) {
                    $list .= '<li>' . $course->description . '</li>';
                }
                $list .= '</ul>';

                return $list;
            })
            ->addColumn('action', function ($forgettingCurve) {

                $f_count = 0;

                $instances = $forgettingCurve->instances;

                $f_count = $instances->sum(function ($instance) {
                    return $instance->steps->sum(function ($step) {
                        return $step->fc_step_progress_count;
                    });
                });

                $f_exams = 0;

                $f_exams = $instances->sum(function ($instance) {
                    return $instance->steps->sum(function ($step) {
                        return $step->exams_count;
                    });
                });

                $f_videos = 0;

                $f_videos = $instances->sum(function ($instance) {
                    return $instance->steps->sum(function ($step) {
                        return $step->video_count;
                    });
                });

                $f_questions = 0;

                $f_questions = $instances->sum(function ($instance) {
                    return $instance->steps->sum(function ($step) {
                        return $step->exams->sum(function ($exam) {
                            return $exam->questions_count;
                        });

                    });
                });

                $f_questions_video = 0;

                $f_questions_video = $instances->sum(function ($instance) {
                    return $instance->steps->sum(function ($step) {
                        return $step->video ? $step->video->questions_count : 0;
                    });
                });

                $btn = '<button data-toggle="modal" data-id="' .
                    $forgettingCurve->id . '" data-url="' . route('admin.forgettingCurve.update', $forgettingCurve) . '"
                                        data-send="' . route('admin.forgettingCurve.edit', $forgettingCurve) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editForgettingCurve-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                if ($f_count == 0 && $f_exams == 0 && $f_videos == 0 && $f_questions == 0 && $f_questions_video == 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $forgettingCurve->id . '" data-original-title="delete"
                                            data-url="' . route('admin.forgettingCurve.destroy', $forgettingCurve) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteForgettingCurve-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'active', 'action', 'course_name'])
            ->make(true);

    }

    public function store($request, $storage)
    {
        $data = normalizeInputStatus($request->validated());

        if ($forgettingCurve = ForgettingCurve::create($data)) {

            $forgettingCurve->courses()->attach($request['courses_id']);
            $instances = $this->instanceService->create($forgettingCurve);
            $steps = $this->stepService->create($instances);

            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'curvadelolvido';
                $belongsTo = 'curvaDelOlvido';
                $relation = 'one_one';
                $file = $request->file('image');

                return $this->fileService->store(
                    $forgettingCurve,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );

            }

        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, ForgettingCurve $forgettingCurve, $storage)
    {

        $data = normalizeInputStatus($request->validated());

        if ($forgettingCurve->update($data)) {

            $this->fileService->destroy($forgettingCurve->file, $storage);

            if ($request->hasFile('image')) {
                $file_type = 'imagenes';
                $category = 'curvadelolvido';
                $belongsTo = 'curvadelolvido';
                $relation = 'one_one';

                $file = $request->file('image');

                $this->fileService->store(
                    $forgettingCurve,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $forgettingCurve;

        }
    }

    public function destroy(ForgettingCurve $forgettingCurve, $storage)
    {
        $instances = $forgettingCurve->instances();
        $forgettingCurve->courses()->detach();
        $this->stepService->destroy($instances);
        $this->instanceService->destroy($instances);

        if ($forgettingCurve->delete()) {

            return $this->fileService->destroy($forgettingCurve->file, $storage);

        }
    }


}



