<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{CourseSection, File, SectionChapter};
use App\Services\{FileService, FreeCourseService, SectionChapterService};
use Exception;
use Illuminate\Http\Request;

class AdminSectionChaptersController extends Controller
{
    private $sectionChapterService;

    public function __construct(SectionChapterService $service)
    {
        $this->sectionChapterService = $service;
    }

    public function getDataTable(Request $request, CourseSection $section)
    {
        if ($request->ajax()) {

            if ($request['type'] == 'html') {

                $html = view('admin.free-courses.partials.chapters-list', compact('section'))->render();

                return response()->json([
                    "html" => $html,
                    'title' => $section->title,
                ]);
            } elseif ($request['type'] == 'table') {
                return $this->sectionChapterService->getDataTable($section->id);
            }
        }
        abort(403);
    }

    public function getVideoData(SectionChapter $chapter)
    {
        $chapter->loadVideo();

        $url_video = verifyFile($chapter->file);

        return response()->json([
            "url_video" => $url_video,
            "section" => $chapter->courseSection->title,
            "chapter" => $chapter->title,
            "url_delete" => route('admin.freeCourses.chapters.deleteVideo', $chapter)
        ]);
    }

    public function store(Request $request, CourseSection $section)
    {
        $success = true;
        $htmlCourse = null;
        $htmlSection = null;
        $htmlChapter = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->sectionChapterService->store($request, $section, $storage);
            $message = config('parameters.stored_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $course = app(FreeCourseService::class)->withFreeCourseRelationshipsQuery()
                ->where('id', $section->course_id)
                ->first();

            $sectionActive = getActiveSection($request['sectionActive']);

            $htmlCourse = view('admin.free-courses.partials.course-box', compact('course'))->render();
            $htmlSection = view('admin.free-courses.partials.sections-list', compact('course', 'sectionActive'))->render();
            $htmlChapter = view('admin.free-courses.partials.chapters-list', compact('section'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "htmlCourse" => $htmlCourse,
            "htmlSection" => $htmlSection,
            "htmlChapter" => $htmlChapter,
            "id" => $section->id,
        ]);
    }

    public function edit(SectionChapter $chapter)
    {
        $chaptersList = SectionChapter::where('section_id', $chapter->section_id)
            ->orderBy('chapter_order', 'ASC')
            ->get(['id', 'section_id', 'chapter_order']);

        return response()->json([
            "chapter" => $chapter,
            "chapters_list" => $chaptersList
        ]);
    }

    public function update(Request $request, SectionChapter $chapter)
    {
        $chapter->loadRelationships();

        $success = true;
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->sectionChapterService->update($request, $chapter, $storage);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $section = $chapter->courseSection;

            $course = app(FreeCourseService::class)->withFreeCourseRelationshipsQuery()
                ->where('id', $section->course_id)
                ->first();

            $htmlCourse = view('admin.free-courses.partials.course-box', compact('course'))->render();
            $htmlChapter = view('admin.free-courses.partials.chapters-list', compact('section'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "htmlChapter" => $htmlChapter,
            "htmlCourse" => $htmlCourse,
            "id" => $section->id
        ]);
    }

    public function destroy(Request $request, SectionChapter $chapter)
    {
        $chapter->loadRelationships();

        $section = $chapter->courseSection;
        $success = true;
        $htmlCourse = null;
        $htmlSection = null;
        $htmlChapter = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->sectionChapterService->destroy($chapter, $storage);
            $message = config('parameters.deleted_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $course = app(FreeCourseService::class)->withFreeCourseRelationshipsQuery()
                ->where('id', $section->course_id)
                ->first();

            $sectionActive = getActiveSection($request['id']);

            $htmlCourse = view('admin.free-courses.partials.course-box', compact('course'))->render();
            $htmlSection = view('admin.free-courses.partials.sections-list', compact('course', 'sectionActive'))->render();
            $htmlChapter = view('admin.free-courses.partials.chapters-list', compact('section'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "htmlCourse" => $htmlCourse,
            "htmlSection" => $htmlSection,
            "htmlChapter" => $htmlChapter ?? null,
            "id" => $section->id
        ]);
    }

    public function deleteVideo(SectionChapter $chapter)
    {
        $chapter->loadRelationships();

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->sectionChapterService->deleteVideo($chapter, $storage);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($success) {
            $section = $chapter->courseSection;
            $htmlChapter = view('admin.free-courses.partials.chapters-list', compact('section'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "htmlChapter" => $htmlChapter ?? null,
            "id" => $chapter->courseSection->id
        ]);
    }



    // * ------------ CONTENT -------------

    public function getContentDetail(SectionChapter $chapter)
    {
        $html = view('admin.free-courses.partials.components._content_chapter_form', compact('chapter'))->render();

        return response()->json([
            'chapter' => $chapter,
            'html' => $html
        ]);
    }

    public function updateContent(Request $request, SectionChapter $chapter)
    {
        try {
            $this->sectionChapterService->updateContent($request, $chapter);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        $html = view('admin.free-courses.partials.components._content_chapter_form', compact('chapter'))->render();

        return response()->json([
            'success' => $success,
            'message' => $message,
            'content' => $chapter->content,
            'html' => $html
        ]);
    }

    // * ------------ FILES --------------

    public function getFilesData(SectionChapter $chapter)
    {
        $chapter->loadFiles();
        $files = $chapter->files;

        $html = view('admin.free-courses.partials.components._files_chapter_list', compact(
                'files',
                'chapter'
            ))->render();

        return response()->json([
            'title' => $chapter->title,
            'html' => $html
        ]);
    }

    public function storeFiles(Request $request, SectionChapter $chapter)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->sectionChapterService->storeFiles($request, $chapter, $storage);

            $chapter->loadFiles()->load(['courseSection']);
            $files = $chapter->files;

            $section = $chapter->courseSection;

            $html = view('admin.free-courses.partials.components._files_chapter_list', compact(
                    'files',
                    'chapter'
                ))->render();
            $htmlChapter = view('admin.free-courses.partials.chapters-list', compact('section'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html ?? null,
            "htmlChapter" => $htmlChapter ?? null,
            "id" => $section->id ?? null
        ]);
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');

        if (app(FileService::class)->validateDownload($file, $storage)) {
            return app(FileService::class)->download($file, $storage);
        }
    }

    public function deleteFile(File $file, SectionChapter $chapter)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $chapter->loadFiles()->load(['courseSection']);

        try {
            $success = $this->sectionChapterService->destroyFile($file, $storage);
            // $participant->loadFilesParticipant();
            $chapter->loadFiles();
            $files = $chapter->files;
            $section = $chapter->courseSection;
            $html = view('admin.free-courses.partials.components._files_chapter_list', compact(
                    'files',
                    'chapter'
                ))->render();
            $htmlChapter = view('admin.free-courses.partials.chapters-list', compact('section'))->render();

        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html ?? null,
            "htmlChapter" => $htmlChapter ?? null,
            "id" => $section->id ?? null
        ]);
    }
}
