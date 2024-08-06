<?php
namespace App\Services;

use App\Models\FcInstance;
use App\Models\FcStep;
use DataTables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


class CurveStepService
{

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function create(Collection $instances)
    {
        $success = '';

        foreach ($instances as $instance) {

            $success = $instance->steps()->createMany([
                [
                    'title' => 'Paso 1',
                    'type' => 'reinforcement',
                    'order' => 1,
                    'active' => 'S',
                ],
                [
                    'title' => 'Paso 2',
                    'type' => 'video',
                    'order' => 2,
                    'active' => 'S',
                ],
                [
                    'title' => 'Paso 3',
                    'type' => 'evaluation',
                    'order' => 3,
                    'active' => 'S',
                ],
            ]);
        }

        return $success;

    }

    public function update(FcStep $step, Request $request, $storage)
    {

        $data = $request->all();
        $data['active'] = isset($data['active']) == 'on' ? 'S' : 'N';

        $instance = $step->instance;
        $instance->steps()->where('order', $data['order'])->update(["order" => $step->order]);

        $updated = $step->update($data);

        if ($updated) {

            if ($request->hasFile('image')) {

                $this->fileService->destroy($step->file, $storage);

                $file_type = 'imagenes';
                $category = 'pasos';
                $file = $request->file('image');
                $belongsTo = 'pasos';
                $relation = 'one_one';

                $this->fileService->store(
                    $step,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $updated;

        }

    }


    public function destroy($instances)
    {
        $instances->each(function ($instance) {
            $instance->steps()->delete();
        });
    }

    public function getDatatable(FcInstance $instance)
    {
        $steps = $instance->steps()->orderBy('order', 'ASC');

        return DataTables::of($steps)
            ->addColumn('title', function ($step) {
                return '<a href="' . route('admin.forgettingCurve.steps.show', $step) . '">' . $step->title . '</a>';
            })
            ->addColumn('description', function ($step) {
                return $step->description ?? '-';
            })
            ->addColumn('type', function ($step) {
                return config('parameters.curve_steps_types')[$step->type];
            })
            ->addColumn('order', function ($step) {
                return $step->order;
            })
            ->addColumn('active', function ($step) {
                $isActive = $step->active == 'S' ? 'active' : 'inactive';
                $nameActive = $step->active == 'S' ? 'Activo' : 'Inactivo';
                $span = '<span class="status ' . $isActive . '">' . $nameActive . '</span>';
                return $span;
            })
            ->addColumn('action', function ($step) {

                $btn = '<button data-toggle="modal" data-id="' .
                    $step->id . '" data-url="' . route('admin.forgettingCurve.steps.update', $step) . '"
                                data-send="' . route('admin.forgettingCurve.steps.edit', $step) . '"
                                data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                editStep-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                return $btn;

            })
            ->rawColumns(['title', 'active', 'action'])
            ->make(true);
    }



    public function storeExam(Request $request, FcStep $step)
    {

        // $courses = $step->instance->forgettingCurve->courses;

        $active = $request->active == 'on' ? 'S' : 'N';

        return $step->exams()->create([
            'title' => $request->title,
            'exam_time' => $request->exam_time,
            'active' => $active,
            'course_id' => null,
        ]);

    }

}



