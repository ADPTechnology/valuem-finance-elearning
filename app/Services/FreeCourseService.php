<?php

namespace App\Services;

use App\Models\{Course, ProductCertification, SectionChapter, User};
use Auth;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FreeCourseService
{
    static function withFreeCourseRelationshipsQuery()
    {
        if (Auth::user()) {
            $query = Course::with(
                    [
                        'courseEvaluations' => fn ($q) =>
                            $q->select(
                                'freecourse_evaluations.id',
                                'freecourse_evaluations.exam_id',
                                'freecourse_evaluations.course_section_id')
                                ->with([
                                    'exam:id,exam_time',
                                    'userEvaluations' => fn ($q) => $q
                                        ->where('user_id', Auth::user()->id)
                                ]),
                        'courseCategory',
                        'courseSections' => fn ($query) =>
                        $query->orderBy('section_order', 'ASC')
                            ->withCount('sectionChapters'),
                        'file' => fn ($query2) =>
                        $query2->where('file_type', 'imagenes')
                            ->where('category', 'cursoslibres')
                    ]
                )
                ->where('course_type', 'FREE')
                ->withCount([
                    'courseSections',
                    'courseChapters'
                ])
                ->withSum(
                    'courseChapters',
                    'duration');
        }
        else {
            $query = Course::with(
                [
                    'courseEvaluations' => fn ($q) =>
                        $q->select(
                            'freecourse_evaluations.id',
                            'freecourse_evaluations.exam_id',
                            'freecourse_evaluations.course_section_id')
                            ->with([
                                'exam:id,exam_time'
                            ]),
                    'courseCategory',
                    'courseSections' => fn ($query) =>
                    $query->orderBy('section_order', 'ASC')
                        ->withCount('sectionChapters'),
                    'file' => fn ($query2) =>
                    $query2->where('file_type', 'imagenes')
                        ->where('category', 'cursoslibres')
                ]
            )
            ->where('course_type', 'FREE')
            ->withCount([
                'courseSections',
                'courseChapters'
            ])
            ->withSum(
                'courseChapters',
                'duration');
        }

        return $query;
    }

    public function attachFreeCourseRelationships($query)
    {

        return $query->with(
            [
                'courseEvaluations' => fn ($q) =>
                    $q->select(
                        'freecourse_evaluations.id',
                        'freecourse_evaluations.exam_id',
                        'freecourse_evaluations.course_section_id')
                        ->with('exam:id,exam_time'),
                'courseCategory',
                'courseSections' => fn ($query) =>
                $query->orderBy('section_order', 'ASC')
                    ->withCount('sectionChapters'),
                'file' => fn ($query2) =>
                $query2->where('file_type', 'imagenes')
                    ->where('category', 'cursoslibres')
            ]
        )

            ->where('course_type', 'FREE')
            ->withCount([
                'courseSections',
                'courseChapters'
            ])
            ->withSum(
                'courseChapters',
                'duration');
    }



    // CLASSROOM FREE COURSE


    public function getPendingAndFinishedCourses($allCourses, $coursesProgress)
    {
        $followingCourses = $allCourses->whereIn('id', $coursesProgress->keys()->all());

        $pendingCourses = collect();
        $finishedCourses = collect();

        foreach ($followingCourses as $followingCourse) {
            $courseProgress = $coursesProgress[$followingCourse->id];
            $Nprogress = getCompletedChapters($courseProgress);

            if ($followingCourse->course_chapters_count == $Nprogress) {
                $finishedCourses->push($followingCourse);
            } else {
                $pendingCourses->push($followingCourse);
            }
        }

        return array($pendingCourses, $finishedCourses);
    }

    public function getNextChapter($nextSections, SectionChapter $currentChapter)
    {
        $next_chapter = null;
        $i = 0;
        foreach ($nextSections as $section) {
            $next_chapter = $i == 0 ?
                $section->sectionChapters
                ->where('chapter_order', $currentChapter->chapter_order + 1)
                ->first()
                : $section->sectionChapters
                ->where('chapter_order', 1)
                ->first();

            if ($next_chapter != null) {
                break;
            }
            $i++;
        }

        return $next_chapter;
    }

    public function getPreviousChapter($previousSections, SectionChapter $currentChapter)
    {
        $previousChapter = null;
        $i = 0;
        foreach ($previousSections as $section) {
            $previousChapter = $i == 0 ?
                $section->sectionChapters
                ->where('chapter_order', $currentChapter->chapter_order - 1)
                ->first()
                : $section->sectionChapters
                ->where('chapter_order', count($section->sectionChapters))
                ->first();

            if ($previousChapter != null) {
                break;
            }

            $i++;
        }

        return $previousChapter;
    }


    // USERS FREE COURSE

    public function getDataTableUsersOnCourse(Course $course, Request $request)
    {
        $query = $course->userProductCertifications()
            ->with([
                'company',
                'productCertifications' => function ($query) use ($course) {
                $query->where('certificable_id', $course->id)->withCount('evaluations');
            }])
            ->select(
                'users.*',
                'product_certifications.id as product_certification_id',
                'product_certifications.status as status_cert',
                'product_certifications.flg_finished as certification_status',
                'product_certifications.score as certification_score'
            );

        if ($request->filled('type')) {

            if ($request['type'] === 'internal') {
                $query->where('users.user_type', null);
            } else {
                $query->where('users.user_type', $request['type']);
            }
        }

        if ($request->filled('statusCertification')) {
            $query->where('status', $request['statusCertification']);
        }

        $usersForCourse = DataTables::of($query)
            ->editColumn('name', function ($user) {
                return $user->full_name_complete;
            })
            ->editColumn('productCertifications.flg_finished', function ($user) {

                return $user->certification_status == 'S' ?
                        '<span class="badge badge-pill badge-success">Finalizado</span>' :
                        '<span class="badge badge-pill badge-warning">Pendiente</span>';

            })
            ->editColumn('productCertifications.score', function ($user) {

                return $user->certification_status == 'S' ? ($user->certification_score ?? '-') : '-';
            })
            ->addColumn('enabled', function ($user) {

                $unlock_cert_checked = $user->status_cert == 'approved' ? 'checked' : '';

                $assit_btn = '<label class="custom-switch">
                                <input type="checkbox" name="unlock_cert"
                                    class="custom-switch-input unlock_cert_user_checkbox" ' . $unlock_cert_checked . '
                                    data-url="' . route('admin.freeCourses.users.updateUnlock', $user->product_certification_id) . '">
                                <span class="custom-switch-indicator"></span>
                            </label>';

                return $assit_btn;
            })
            ->addColumn('action', function ($user) {

                if ($user->certification_status == 'S') {
                    $btn = '<a href="javascript:void(0)" data-id="' .
                                $user->id . '" data-original-title="delete"
                                title="Restablecer evaluación final del usuario"
                                data-url="' . route('admin.freeCourses.users.resetCertification', $user->product_certification_id) . '" class="ms-3 reset-free-course-cert btn btn-primary btn-sm">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </a>';
                } else {
                    $btn = '<a href="javascript:void(0)" data-id="' .
                                $user->id . '" data-original-title="delete"
                                title="Restablecer evaluación final del usuario"
                                class="ms-3 btn btn-primary btn-sm disabled">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </a>';
                }

                if ($user->productCertifications->first()->evaluations_count === 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $user->id . '" data-original-title="delete"
                                        data-url="' . route('admin.freeCourses.users.destroy', $user->product_certification_id) . '" class="ms-3 delete-btn-user-course btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>';
                } else {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $user->id . '" data-original-title="delete"
                                        class="ms-3 btn btn-danger btn-sm disabled">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>';
                }

                return $btn;
            })
            ->rawColumns(['productCertifications.flg_finished', 'enabled', 'action'])
            ->make(true);

        return $usersForCourse;
    }

    public function updateUnlockCert($request, ProductCertification $productCertification)
    {
        $request['unlock_cert'] = $request['unlock_cert'] == 'true' ? 'approved' : 'pending';

        $dataStatus = [
            'status' => $request['unlock_cert']
        ];

        return $productCertification->update($dataStatus);
    }

    public function resetCertification(ProductCertification $productCertification)
    {
        $productCertification->load([
                                'course' => fn ($q) => $q
                                    ->with([
                                        'courseSections' => fn ($q) => $q
                                            ->has('fcEvaluations')
                                    ]),
                                'user'
                            ]);

        $last_section = $productCertification->course->courseSections->sortByDesc('section_order')->first();

        $user = $productCertification->user;

        // $user->progressChapters()->whereHas('courseSection', function ($q) use ($productCertification) {
        //             $q->where('course_id', $productCertification->course->id);
        //         })->detach();

        $productCertification->load([
                                    'evaluations' => fn ($q) => $q
                                        ->whereHas('fcEvaluation', function ($q) use ($last_section) {
                                            $q->where('course_section_id', $last_section->id);
                                        })
                                ]);

        $productCertification->evaluations->each(function ($evaluation) {
            $evaluation->evaluations()->delete();
        });

        $productCertification->evaluations()
                            ->whereHas('fcEvaluation', function ($q) use ($last_section) {
                                            $q->where('course_section_id', $last_section->id);
                            })->delete();

        // $productCertification->evaluations()->delete();

        return $productCertification->update([
                    "flg_finished" => 'N',
                    "score" => null
                ]);
    }

    public function destroyUserForCourse(ProductCertification $productCertification)
    {
        $productCertification->load(['course', 'user']);

        $user = $productCertification->user;

        $user->progressChapters()->whereHas('courseSection', function ($q) use ($productCertification) {
                    $q->where('course_id', $productCertification->course->id);
                })->detach();

        return $productCertification->delete();
    }

    public function getUsersTableCourse(Request $request, Course $course)
    {

        $participants = $course->userProductCertifications()
            ->get(['user_id'])
            ->pluck('user_id')
            ->toArray();


        $users = User::where('role', 'participants')
            ->whereNotIn('users.id', $participants)
            ->with('company')
            ->select('users.*');

        if ($request->filled('search_company')) {
            $users = $users->where('company_id', $request['search_company']);
        }

        $allUsers = DataTables::of($users)
            ->addColumn('choose', function ($user) {
                $checkbox = '<div class="custom-checkbox custom-control">
                                            <input type="checkbox" name="users-selected[]"
                                             class="custom-control-input checkbox-user-input" id="checkbox-' . $user->id . '" value="' . $user->dni . '">
                                            <label for="checkbox-' . $user->id . '" class="custom-control-label checkbox-user-label">&nbsp;</label>
                                        </div>';
                return $checkbox;
            })
            ->editColumn('company.description', function ($user) {
                return $user->company->description ?? '-';
            })
            ->editColumn('user.name', function ($user) {
                return $user->full_name;
            })
            ->rawColumns(['choose'])
            ->make(true);

        return $allUsers;
    }

    // REGISTER PARTICIPANT

    public function storeParticipantFreeCourse($dnis, Course $course)
    {

        $users = $this->getFilteredUsers($dnis, $course);

        [$certifications] = $this->getCertificationsArray($users, $course);

        $course->productCertifications()->saveMany($certifications);

        return array("success" => true);
    }

    public function getFilteredUsers($dnis, Course $course)
    {
        $usersDnis = $course->userProductCertifications->map(function ($user) {
            return $user->dni;
        })->toArray();

        $filteredDnis = array_diff($dnis, $usersDnis);

        return User::whereIn('dni', $filteredDnis)->get();
    }

    public function getCertificationsArray($users, Course $course)
    {
        $certifications = [];

        foreach ($users as $i => $user) {

            $certifications[] = new ProductCertification([
                'user_id' => $user->id,
                'status' => 'approved',
            ]);
        }

        return [$certifications];
    }



    // ADMIN FREE COURSE

    public function getCoursesDataTable(int $category_id = null)
    {
        $query = Course::with([
            'courseEvaluations' => fn ($q) =>
            $q->select(
                'freecourse_evaluations.id',
                'freecourse_evaluations.exam_id',
                'freecourse_evaluations.course_section_id')
                ->with('exam:id,exam_time'),
            'courseCategory',
        ])

            ->withCount(['courseSections', 'courseChapters'])
            ->withSum('courseChapters', 'duration')
            ->where('course_type', 'FREE');

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        $allCourses = DataTables::of($query)
            ->editColumn('description', function ($course) {
                return '<a href="' . route('admin.freeCourses.courses.index', $course) . '">' . $course->description . '</a>';
            })
            ->editColumn('course_category.description', function ($course) {
                $category = $course->courseCategory;
                return '<a href="' . route('admin.freeCourses.categories.index', $category) . '">' . $course->courseCategory->description . '</a>';
            })
            ->addColumn('sections', function ($course) {
                return $course->course_sections_count;
            })
            ->addColumn('chapters', function ($course) {
                return $course->course_chapters_count;
            })
            ->addColumn('duration', function ($course) {
                return $course->hours . ' hrs.'; #getFreeCourseTime($course->course_chapters_sum_duration + $course->exams_duration);
            })
            ->editColumn('active', function ($course) {
                $status = $course->active;
                return '<span class="status ' . getStatusClass($status) . '">' .
                    getStatusText($status) .
                    '</span>';
            })
            ->addcolumn('recom', function ($course) {
                return getStatusRecomended($course->flg_recom);
            })
            ->rawColumns(['description', 'course_category.description', 'active', 'recom'])
            ->make(true);

        return $allCourses;
    }


    public function store($request, $storage)
    {
        $data = normalizeInputStatus($request->validated());

        $dataFC = [
            "description" => $data['description'],
            "subtitle" => $data['subtitle'],
            "hours" => $data['hours'],
            "category_id" => $data['category_id'],
            "active" => $data['active'],
            "min_score" => $data['min_score'],
            "flg_recom" => $data['flg_recom'],
        ];

        $course = Course::create($dataFC + [
            "course_type" => 'FREE',
            "date" => getCurrentDate(),
            "time_start" => '0:00:00',
            "time_end" => '0:00:00'
        ]);

        if ($course) {

            $course->freecourseDetail()->create([
                "price" => $data['price'],
                "description" => $data['description_details']
            ]);

            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'cursoslibres';
                $belongsTo = 'cursoslibres';
                $relation = 'one_one';

                $file = $request->file('image');

                app(FileService::class)->store(
                    $course,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $course;
        }

        throw new Exception(config('parameters.exception_message'));
    }


    public function update($request, $storage, Course $course)
    {
        $data = normalizeInputStatus($request->validated());

        $isUpdated = $course->update($data);

        $course->freecourseDetail()->update([
            "price" => $data['price'],
            "description" => $data['description_details']
        ]);

        if ($isUpdated) {
            if ($request->hasFile('image')) {
                app(FileService::class)->destroy($course->file, $storage);

                $file_type = 'imagenes';
                $category = 'cursoslibres';
                $file = $request->file('image');
                $belongsTo = 'cursoslibres';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $course,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $isUpdated;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy($storage, Course $course)
    {
        if (app(FileService::class)->destroy($course->file, $storage)) {
            $isDeleted = $course->delete();
            if ($isDeleted) {
                return true;
            }
        };

        throw new Exception(config('parameters.exception_message'));
    }

    public function attachSectionRelationships($query)
    {
        return $query
            //->has('fcEvaluations')
            ->with([
                'sectionChapters' => fn ($q)
                    => $q->with([
                        'progressUsers' => fn ($q)
                            => $q->where('user_id', Auth::user()->id)
                ])
            ])
            ->orderBy('section_order', 'ASC')
            ->withSum('sectionChapters', 'duration')
            ->withCount('sectionChapters');
    }

    // FILES

    public function getFilesDataTable(Course $course)
    {
        $query = $course->files()->where('file_type', 'archivos');

        return DataTables::of($query)
            ->editColumn('file_path', function ($file) {
                return '<a href="' . route('admin.freeCourses.files.download', $file) . '">' . $file->name . '</a> ';
            })
            ->editColumn('file_type', function ($file) {
                return ucwords($file->file_type);
            })
            ->editColumn('category', function ($file) {
                return ucwords($file->category);
            })
            ->editColumn('created_at', function ($file) {
                return $file->created_at;
            })
            ->editColumn('updated_at', function ($file) {
                return $file->updated_at;
            })
            ->addColumn('action', function ($file) use ($course) {

                $btn = '<a href="javascript:void(0)" data-id="' .
                    $file->id . '" data-original-title="delete"
                                    data-url="' . route('admin.freeCourses.files.destroy', ['course' => $course, 'file' => $file]) . '" class="ms-3 edit btn btn-danger btn-sm
                                    deleteFile"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->rawColumns(['file_path', 'action'])
            ->make(true);
    }

    public function storeFiles($request, Course $course, $storage)
    {
        $file_type = 'archivos';
        $category = 'cursoslibres';
        $belongsTo = 'cursoslibres';
        $relation = 'one_many';

        foreach ($request->file('files') as $file) {

            $stored = app(FileService::class)->store(
                $course,
                $file_type,
                $category,
                $file,
                $storage,
                $belongsTo,
                $relation
            );
        }

        if ($stored) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }
}
