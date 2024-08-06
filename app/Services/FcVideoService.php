<?php

namespace App\Services;

use App\Models\DynamicQuestion;
use App\Models\FcStep;
use App\Models\FcVideo;
use App\Models\FcVideoQuestion;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class FcVideoService
{
    protected $fileService;
    protected $dynamicAlternativeService;

    public function __construct(FileService $fileService, DynamicAlternativeService $dynamicAlternativeService)
    {
        $this->fileService = $fileService;
        $this->dynamicAlternativeService = $dynamicAlternativeService;
    }


    public function store(Request $request, FcStep $step, $storage)
    {
        if ($request->hasFile('video') && $step->type == 'video') {

            $video = null;

            $step->load('video');

            if ($step->video) {
                $video = $step->video;
            } else {
                $video = $step->video()->create([
                    'max_score' => 0,
                ]);
            }

            $file_type = 'videos';
            $category = 'steps';
            $file = $request->file('video');
            $belongsTo = 'steps';
            $relation = 'one_one';

            return $this->fileService->store(
                $video,
                $file_type,
                $category,
                $file,
                $storage,
                $belongsTo,
                $relation
            );
        }
    }

    public function destroy(FcVideo $video, $storage)
    {
        if ($this->fileService->destroy($video->file, $storage)) {
            return $video->delete();
        }
    }

    //  QUESTIONS VIDEO

    public function storeQuestion(Request $request, FcVideo $video, $storage)
    {

        $data = normalizeInputStatus($request->all());
        $question = $video->questions()->create($data + ['type' => 'VIDEO']);

        if ($question) {

            $isStored = $this->dynamicAlternativeService->storeAll($request, $question, $storage);

            if ($isStored) {
                return $question;
            }
        }

        throw new Exception('No es posible completar la solicitud');

    }

    public function updateQuestion(Request $request, FcVideoQuestion $question)
    {

        $question->update([
            'statement' => $request->statement,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
        ]);

        return $question;

    }


    public function destroyQuestion(DynamicQuestion $question, $storage)
    {
        $alternativesAreDeleted = app(DynamicAlternativeService::class)->destroyAll($question, $storage);

        if ($alternativesAreDeleted) {

            foreach ($question->files as $file) {
                app(FileService::class)->destroy($file, $storage);
            }

            $isDeleted = $question->delete();

            if ($isDeleted) {
                return true;
            }
        }

        throw new Exception('No es posible completar la solicitud');

    }


    public function getDatatableQuestions(FcVideo $video)
    {

        $query = DynamicQuestion::with(['questionType:id,description'])
            ->withCount(['evaluations']);

        if ($video) {
            $query->where([
                'f_curve_video_id' => $video->id,
                'type' => 'VIDEO'
            ]);
        }

        $allQuestions = DataTables::of($query)
            ->editColumn('statement', function ($question) {
                return '<a href="' . route('admin.forgettingCurve.steps.video.questionVideo.show', $question) . '">' . $question->statement . '</a>';
            })
            ->editColumn('created_at', function ($question) {
                return $question->created_at;
            })
            ->editColumn('updated_at', function ($question) {
                return $question->updated_at;
            })
            ->editColumn('active', function ($question) {
                $status = $question->active;
                $statusBtn = '<span class="status ' . getStatusClass($status) . '">' . getStatusText($status) . '</span>';

                return $statusBtn;
            })
            ->addColumn('action', function ($question) {

                if ($question->evaluations_count == 0) {
                    $btn = '<a href="javascript:void(0)" data-id="' .
                        $question->id . '" data-original-title="delete"
                                        data-url="' . route('admin.forgettingCurve.steps.video.questionVideo.destroy', $question) . '" class="ms-3 edit btn btn-danger btn-sm
                                        deleteQuestion-btn"><i class="fa-solid fa-trash-can"></i></a>';
                } else {
                    $btn = '<a href="javascript:void(0)" data-id="' .
                        $question->id . '" data-original-title="delete" class="ms-3 btn btn-danger disabled btn-sm"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['statement', 'action', 'active'])
            ->make(true);

        return $allQuestions;

    }


}



