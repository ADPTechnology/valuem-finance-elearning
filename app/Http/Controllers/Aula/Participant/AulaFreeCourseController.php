<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\{
    Course,
    CourseCategory,
    CourseSection,
    File,
    SectionChapter,
    User
};
use App\Services\{CourseCategoryService, FolderService, FreeCourseService};

class AulaFreeCourseController extends Controller
{
    private $freeCourseService;
    private $folderService;

    public function __construct(FreeCourseService $service, FolderService $folderService)
    {
        $this->freeCourseService = $service;
        $this->folderService = $folderService;
    }

    public function index()
    {
        $user = Auth::user();
        $categories = app(CourseCategoryService::class)->withCategoryRelationshipsQuery()
            ->where('status', 'S')
            ->select('id', 'description')
            ->get();

        $coursesProgress = $user->progressChapters()
            ->with('courseSection:id,course_id')
            ->select('section_chapters.id', 'section_chapters.section_id')
            ->get()
            ->groupBy('courseSection.course_id');

        $allCourses = $this->freeCourseService->withFreeCourseRelationshipsQuery()
            ->whereHas('productCertifications', function ($query) use ($user) {
                $query->where([
                    'user_id' => $user->id,
                    'status' => 'approved'
                ]);
            })
            ->where('active', 'S')
            ->having('course_chapters_count', '>', 0)
            ->get();

        $recomendedCourses = $allCourses->where('flg_recom', 1);

        $followingCourses = $this->freeCourseService->getPendingAndFinishedCourses($allCourses, $coursesProgress);

        $pendingCourses = $followingCourses[0];
        $finishedCourses = $followingCourses[1];

        return view('aula.viewParticipant.freecourses.index', [
            'recomendedCourses' => $recomendedCourses,
            'categories' => $categories,
            'pendingCourses' => $pendingCourses,
            'finishedCourses' => $finishedCourses
        ]);
    }

    public function start(Course $course)
    {
        $user = Auth::user();

        if ($course->active == 'S') {
            $progress = $user->progressChapters()
                ->wherePivot('last_seen', 1)
                ->whereHas('courseSection', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->select('section_chapters.id', 'section_chapters.section_id')
                ->first();

            if ($progress == null) {
                $current_chapter = $course->courseSections()
                    ->select('id', 'section_order', 'course_id')
                    ->where('section_order', 1)
                    ->with([
                        'sectionChapters' => fn ($query) => $query
                            ->select('id', 'section_id', 'chapter_order')
                            ->where('chapter_order', 1)
                    ])
                    ->first()->sectionChapters->first();

                $verifyProgress = $user->progressChapters()
                    ->where('section_chapter_id', $current_chapter->id)
                    ->first();

                if ($verifyProgress == null) {
                    $user->progressChapters()->attach($current_chapter, [
                        'progress_time' => 0,
                        'last_seen' => 1,
                        'status' => 'P'
                    ]);
                } else {
                    $user->progressChapters()->updateExistingPivot($current_chapter, [
                        'last_seen' => 1,
                    ]);
                }
            } else {
                $current_chapter = $progress;
            }

            return redirect()->route('aula.freecourse.showChapter', [
                'course' => $course,
                'current_chapter' => $current_chapter
            ]);
        } else {
            return redirect()->route('aula.freecourse.index');
        }
    }

    public function showCategory(CourseCategory $category)
    {
        $user = Auth::user();

        $courses = $this->freeCourseService->attachFreeCourseRelationships($category->courses())
            ->whereHas('productCertifications', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'approved');
            })
            ->where('active', 'S')
            ->having('course_chapters_count', '>', 0)
            ->get();

        return view('aula.viewParticipant.freecourses.showCategory', [
            'category' => $category,
            'courses' => $courses
        ]);
    }

    public function showChapter(Course $course, SectionChapter $current_chapter)
    {
        $user = Auth::user();

        if ($course->active == 'S') {

            $course->loadFreeCourseImage();

            $productCertification = $course
                                    ->productCertifications()
                                    ->where('user_id', Auth::user()->id)
                                    ->first();

            $allProgress = $user->progressChapters()->whereHas('courseSection', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
                ->select(
                    'section_chapters.id',
                    'section_chapters.section_id',
                    'section_chapters.title',
                    'section_chapters.description',
                    'section_chapters.chapter_order'
                )
                ->get();

            $sections = $this->freeCourseService->attachSectionRelationships($course->courseSections())
                    ->with([
                        'fcEvaluations' => fn ($q) => $q
                        ->with([
                           'exam',
                           'userEvaluations' => fn ($q) => $q
                               ->where('p_certification_id', $productCertification->id)
                       ])
                    ])
                    ->having('section_chapters_count', '>', 0)
                    ->get();

            $current_chapter = $allProgress->where('id', $current_chapter->id)->first();

            if ($current_chapter != null) {
                $current_time = $current_chapter->pivot->progress_time;
                $current_chapter->loadVideo();
            } else {
                return redirect()->route('aula.freecourse.index');
            }

            $current_section = $sections->filter(function ($section) use ($current_chapter) {
                return $section->id == $current_chapter->section_id;
            })->first();

            $next_sections = $sections->whereIn(
                'section_order',
                [
                    $current_section->section_order,
                    $current_section->section_order + 1
                ]
            );

            $next_sections = $sections->where('section_order', '>=', $current_section->section_order)->take(2);
            $next_chapter = $this->freeCourseService->getNextChapter($next_sections, $current_chapter);

            $previous_sections = $sections->where('section_order', '<=', $current_section->section_order)->reverse()->take(2);
            $previous_chapter = $this->freeCourseService->getPreviousChapter($previous_sections, $current_chapter);

            $files = $course->files()
                ->where('file_type', 'archivos')
                ->where('category', 'cursoslibres')->get();

            $itsLastChapterOfSection = $current_section->sectionChapters
                                            ->where('chapter_order', $current_chapter->chapter_order + 1)
                                            ->count() > 0 ? false : true;

            // dd($sections);

            return view('aula.viewParticipant.freecourses.showChapter', [
                'course' => $course,
                'files' => $files,
                'sections' => $sections,
                'current_chapter' => $current_chapter,
                'current_section' => $current_section,
                'next_chapter' => $next_chapter,
                'previous_chapter' => $previous_chapter,
                'current_time' => $current_time,
                'allProgress' => $allProgress,
                'productCertification' => $productCertification,
                "itsLastChapterOfSection" => $itsLastChapterOfSection
            ]);
        } else {
            return redirect()->route('aula.freecourse.index');
        }
    }

    public function downloadFile(File $file)
    {
        $storage = env('FILESYSTEM_DRIVER');
        return $this->folderService->downloadFile($file, $storage);
    }

    public function updateChapter(SectionChapter $current_chapter, SectionChapter $new_chapter, Course $course)
    {
        $user = Auth::user();

        if (!$this->isValidUpdate($new_chapter, $user)) {
            abort(401);
        }

        $lastSeenChapter = $user->progressChapters()->whereHas('courseSection.course', function ($query) use ($course) {
            $query->where('id', $course->id);
        })
        ->wherePivot('last_seen', 1)
        ->select('section_chapters.id', 'section_chapters.section_id')
        ->first();

        $user->progressChapters()->updateExistingPivot($lastSeenChapter, [
            'last_seen' => 0,
        ]);

        $next_relation = $user->progressChapters()->wherePivot('section_chapter_id', $new_chapter->id)
                        ->select('section_chapters.id', 'section_chapters.section_id')
                        ->first();

        if ($next_relation == null) {

            $user->progressChapters()->attach($new_chapter, [
                'progress_time' => 0,
                'last_seen' => 1,
                'status' => 'P'
            ]);

        } else {
            $user->progressChapters()->updateExistingPivot($new_chapter, [
                'last_seen' => 1,
            ]);
        }

        return redirect()->route('aula.freecourse.showChapter', [
            'course' => $course,
            'current_chapter' => $new_chapter
        ]);
    }

    public function updateProgressTime(Request $request, SectionChapter $current_chapter)
    {
        $user = Auth::user();
        $time = floor($request->time);
        $duration = floor($request->duration);

        $checkFinished = $user->progressChapters()->where('section_chapter_id', $current_chapter->id)
                                ->first()->pivot->status == 'F' ? true : false;

        if ($time / $duration >= 0.50 && !$checkFinished) {

            $user->progressChapters()->updateExistingPivot($current_chapter, [
                'progress_time' => $time,
                'status' => 'F'
            ]);

            if ($request->incompleteFlag) {

                $current_chapter->load([
                    'courseSection' => fn ($q) => $q
                        ->select(
                            'course_sections.id',
                            'course_sections.course_id'
                            )
                        ->with([
                            'sectionChapters' => fn ($q) => $q
                            ->select(
                                'section_chapters.id',
                                'section_chapters.section_id'
                            )
                            ->with([
                                    'progressUsers' => fn ($q) => $q
                                    ->where('user_id', Auth::user()->id)
                                ]),
                        ])
                        ->withCount('sectionChapters')
                ]);

                $section = $current_chapter->courseSection;

                if ($section->section_chapters_count == getFinishedChaptersCountBySection($section)) {

                    $completedChapters = true;

                    $section->load('fcEvaluations:id,course_section_id,exam_id');
                    $evaluation = $section->fcEvaluations->first();

                    $productCertification_id = $request->productCertificationId;

                    $evaluation->load([
                        'userEvaluations' => fn ($q) => $q
                        ->where('p_certification_id', $productCertification_id)
                    ]);

                    $htmlBtn = view('aula.viewParticipant.freecourses.components._start_evaluation_btn', compact(
                        'section',
                        'evaluation',
                        'productCertification_id'
                    ))->render();
                }

                $checkFinished = true;
            }
            // $checkFinished = true;
        }
        else {
            $user->progressChapters()->updateExistingPivot($current_chapter, [
                'progress_time' => $time,
                // 'status' => 'P'
            ]);
        }

        return response()->json(
            [
                'check' => $checkFinished,
                'completedChapters' => $completedChapters ?? false,
                'htmlBtn' => $htmlBtn ?? null
        ]);
    }

    private function isValidUpdate(SectionChapter $chapter, User $user)
    {
        $chapter->load('courseSection:id,section_order,course_id');

        $prev_section = CourseSection::where('course_id', $chapter->courseSection->course_id)
                                    ->where('section_order', $chapter->courseSection->section_order - 1)
                                    ->first();

        if ($prev_section) {

            $prev_section->load([
                            'sectionChapters' => fn ($q) =>
                                $q->with([
                                    'progressUsers' => fn ($q)
                                        => $q->where('user_id', $user->id),
                                ]),
                            'fcEvaluations' => fn ($q) => $q
                                    ->with([
                                    'userEvaluations' => fn ($q) => $q
                                        ->where('user_id', $user->id)
                                ])
                        ])
                        ->loadCount('sectionChapters');

            $prev_evaluation = $prev_section->fcEvaluations->first() ?? null;

            if ($prev_evaluation) {
                $prev_prodCertificationWithPivot = $prev_evaluation->userEvaluations->first() ?? null;
                $isValid = $prev_prodCertificationWithPivot && ($prev_prodCertificationWithPivot->pivot->status === 'finished') ? true : false;
            }
            else {
                $isValid = true;
            }
        }

        return $isValid ?? true;
    }
}
