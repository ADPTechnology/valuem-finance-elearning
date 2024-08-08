<?php

namespace App\Services;

use App\Models\{CourseSection, File, SectionChapter};
use Datatables;
use Exception;
use Owenoj\LaravelGetId3\GetId3;
use Request;

class SectionChapterService
{
    public function getDataTable($section_id)
    {
        $query = SectionChapter::where('section_id', $section_id)
                                ->with([
                                    'file' => fn($q) =>
                                        $q->where('file_type', 'videos')
                                ])
                                ->withCount([
                                    'files as files_general_count' => fn ($q) =>
                                        $q->where('file_type', 'archivos')
                                ]);

        $allChapters = Datatables::of($query)
            ->editColumn('duration', function ($chapter) {
                return $chapter->duration . ' minutos';
            })
            ->editColumn('description', function ($chapter) {
                $description = $chapter->description;
                if (strlen($chapter->description) > 100) {
                    $description =  mb_substr($chapter->description, 0, 100, 'UTF-8') . ' ...';
                }
                return $description;
            })
            ->addColumn('view', function ($chapter) {
                if ($chapter->file) {
                        $btn ='<a href="javascript:void(0);" class="preview-chapter-video-button"
                                data-url="' . route('admin.freeCourses.chapters.getVideoData', $chapter) . '">
                                <i class="fa-solid fa-video"></i>
                            </a>';
                }

                return $btn ?? '-';
            })
            ->addColumn('content', function ($chapter) {

                $btn = '<button data-id="' . $chapter->id . '"
                            data-send="'. route('admin.freeCourses.chapters.getContentDetail', $chapter) .'"
                            data-url="'. route('admin.freeCourses.chapters.updateContent', $chapter) .'"
                            data-original-title="edit" class="me-3 edit btn btn-dark btn-sm
                            showContentChapter-btn">
                                <i class="fa-solid fa-person-chalkboard"></i>
                        </button>';

                $btn .= '<button data-id="' . $chapter->id . '"
                            data-send="'. route('admin.freeCourses.chapters.getFilesData', $chapter) .'"
                            data-url="'. route('admin.freeCourses.chapters.storeFiles', $chapter) .'"
                            data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                            showDocsChapter">
                            <i class="fa-solid fa-file-lines"></i>
                        </button>';

                return $btn;
            })
            ->addColumn('action', function ($chapter) {
                $btn = '<button data-id="' . $chapter->id . '"
                                    data-url="' . route('admin.freeCourses.chapters.update', $chapter) . '"
                                    data-send="' . route('admin.freeCourses.chapters.edit', $chapter) . '"
                                    data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                    editChapter"><i class="fa-solid fa-pen-to-square"></i>
                        </button>';

                if ($chapter->files_general_count == 0) {

                    $btn .= '<button href="javascript:void(0)" data-id="' .
                    $chapter->id . '" data-original-title="delete"
                                    data-url="' . route('admin.freeCourses.chapters.delete', $chapter) .
                    '" class="ms-3 delete btn btn-danger btn-sm
                                    deleteChapter"><i class="fa-solid fa-trash-can"></i></button>';
                }

                return $btn;
            })
            ->rawColumns(['view', 'content', 'action'])
            ->make(true);

        return $allChapters;
    }

    public function store($request, CourseSection $section, $storage)
    {
        $lastOrder = $section->getChapterLastOrder();

        $chapter = SectionChapter::create($request->all() + [
            "chapter_order" => $lastOrder + 1,
            "duration" => 0,
            "section_id" => $section->id
        ]);

        if ($request->hasFile('file')) {

            $video = $request->file('file');
            $videoId3 = new GetId3($video);
            $duration = round($videoId3->getPlaytimeSeconds() / 60);

            $chapter->update([
                'duration' => $duration
            ]);

            if ($chapter) {
                $file_type = 'videos';
                $category = 'cursoslibres';
                $belongsTo = 'cursoslibres';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $chapter,
                    $file_type,
                    $category,
                    $video,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }
        }

        return $chapter;

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, SectionChapter $chapter, $storage)
    {
        $duration = $chapter->duration;
        $order = $request['chapter_order'];

        if ($order != $chapter->chapter_order) {
            SectionChapter::where('section_id', $chapter->section_id)
                ->where('chapter_order', $order)
                ->update([
                    "chapter_order" => $chapter->chapter_order
                ]);
        }

        if ($request->has('file')) {
            app(FileService::class)->destroy($chapter->file, $storage);

            $video = $request->file('file');
            $videoId3 = new GetId3($video);
            $duration = round($videoId3->getPlaytimeSeconds() / 60);

            $file_type = 'videos';
            $category = 'cursoslibres';
            $belongsTo = 'cursoslibres';
            $relation = 'one_one';

            app(FileService::class)->store(
                $chapter,
                $file_type,
                $category,
                $video,
                $storage,
                $belongsTo,
                $relation
            );
        }

        $isUpdated = $chapter->update($request->all() + [
            "duration" => $duration
        ]);

        if ($isUpdated) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(SectionChapter $chapter, $storage)
    {
        $section_id = $chapter->courseSection->id;
        $chapter->progressUsers()->detach();

        if ($chapter->file) {
            app(FileService::class)->destroy($chapter->file, $storage);
        }

        $isDeleted = $chapter->delete();

        if ($isDeleted) {
            return $this->updateAllOrders($section_id);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function updateAllOrders($section_id)
    {
        $chapters = SectionChapter::where('section_id', $section_id)
            ->orderBy('chapter_order', 'ASC')->get();

        $order = 1;
        foreach ($chapters as $remanentChapter) {
            $remanentChapter->update([
                "chapter_order" => $order
            ]);
            $order++;
        }

        return true;
    }


    public function deleteVideo(SectionChapter $chapter, $storage)
    {
        if ($chapter->file) {
            app(FileService::class)->destroy($chapter->file, $storage);

            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function updateContent($request, SectionChapter $chapter)
    {
        return $chapter->update([
            'content' => $request['content']
        ]);
    }

    public function storeFiles($request, SectionChapter $chapter, $storage)
    {
        if ($request->hasFile('files')) {

            $file_type = 'archivos';
            $category = 'cursoslibres';
            $belongsTo = 'cursoslibres';
            $relation = 'one_many';

            $success = [];

            $files = $request->file('files');

            foreach ($files as $file) {
                if (
                    app(FileService::class)->store(
                        $chapter,
                        $file_type,
                        $category,
                        $file,
                        $storage,
                        $belongsTo,
                        $relation
                    )
                ) {
                    array_push($success, $file);
                };
            }

            return count($files) == count($success);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroyFile(File $file, $storage)
    {
        return app(FileService::class)->destroy($file, $storage);
    }
}
