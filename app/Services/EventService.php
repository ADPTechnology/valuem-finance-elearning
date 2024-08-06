<?php

namespace App\Services;

use App\Models\{Course, CourseModule, Event, User};
use App\Models\Exam;
use App\Models\GroupEvent;
use App\Models\OwnerCompany;
use App\Models\ParticipantGroup;
use App\Models\Room;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EventService
{
    public function getDataTable(Request $request)
    {
        $query = Event::with([
            'exam.course',
            'user',
            'responsable',
        ])
            ->withCount(['certifications', 'userSurveys'])
            ->doesntHave('courseModule');

        if ($request->filled('from_date') && $request->filled('end_date')) {
            $query = $query->whereBetween('date', [$request->from_date, $request->end_date]);
        }

        if ($request->filled('search_course')) {
            $query = $query->whereHas('exam.course', function ($query) use ($request) {
                $query->where('id', $request['search_course']);
            });
        }

        if ($request->filled('search_instructor')) {
            $query = $query->where('user_id', $request['search_instructor']);
        }

        if ($request->filled('search_responsable')) {
            $query = $query->where('responsable_id', $request['search_responsable']);
        }

        $allEvents = DataTables::of($query)
            ->editColumn('description', function ($event) {
                return '<a href="' . route('admin.events.show', $event) . '">' . $event->description . '</a>';
            })
            ->editColumn('type', function ($event) {
                return config('parameters.event_types')[verifyEventType($event->type)];
            })
            ->editColumn('user.name', function ($event) {
                $user = $event->user;
                return $user->full_name;
            })
            ->editColumn('responsable.name', function ($event) {
                $responsable = $event->responsable;
                return $responsable->full_name;
            })
            ->editColumn('flg_asist', function ($event) {
                return $event->flg_asist == 'S' ? 'Habilitado' : 'Deshabilitado';
            })
            ->editColumn('active', function ($event) {
                $status = $event->active;
                $statusBtn = '<span class="status ' . getStatusClass($status) . '">' . getStatusText($status) . '</span>';

                return $statusBtn;
            })
            ->addColumn('action', function ($event) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $event->id . '" data-url="' . route('admin.events.update', $event) . '"
                                        data-send="' . route('admin.events.edit', $event) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editEvent-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $event->certifications_count == 0 &&
                    $event->user_surveys_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $event->id . '" data-original-title="delete"
                                            data-url="' . route('admin.events.destroy', $event) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteEvent-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['description', 'active', 'action'])
            ->make(true);

        return $allEvents;
    }

    public function getTypes()
    {
        return collect(config('parameters.event_types'));
    }

    public function store($request)
    {
        $data = normalizeInputStatus($request->validated());
        $event = Event::create($data);

        if ($event) {
            return $event;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, Event $event)
    {
        $data = normalizeInputStatus($request->validated());

        if ($event->finished_certifications_count != 0) {
            $data['exam_id'] = $event->exam_id;
            $data['min_score'] = $event->min_score;
            $data['questions_qty'] = $event->questions_qty;
        }

        if ($event->update($data)) {
            return true;
        }

        throw new Exception('No es posible completar la solicitud');
    }

    public function destroy(Event $event)
    {
        $isDeleted = $event->delete();

        if ($isDeleted) {
            return true;
        }

        throw new Exception('No es posible completar la solicitud');
    }

    public function getUsersTable(Request $request, Event $event)
    {
        $participants = $event->participants()->wherePivot('evaluation_type', 'certification')->get()
            ->pluck('id')->toArray();

        $users = User::where('role', 'participants')
            ->where(function ($q) {
                $q->where('user_type', '!=', 'external')->orWhereNull('user_type');
            })
            ->whereNotIn('users.id', $participants)
            ->with('company');

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
                return $user->company != null ? $user->company->description : '-';
            })
            ->editColumn('user.name', function ($user) {
                return $user->full_name;
            })
            ->rawColumns(['choose'])
            ->make(true);

        return $allUsers;
    }


    // --------------- INSTRUCTOR ----------------------

    public function getInstructorEventsDatatable(User $user, Course $course)
    {
        $query = Event::whereHas('course', function ($q) use ($course) {
            $q->where('courses.id', $course->id);
        })
            ->where('user_id', $user->id)
            ->with([
                'room',
                'exam.course'
            ])
            ->doesntHave('courseModule')
            ->select('events.*');

        $allEvents = DataTables::of($query)
            ->editColumn('description', function ($event) {
                return '<a href="' . route('aula.course.events.instructor.show', $event) . '">' . $event->description . '</a>';
            })
            ->editColumn('type', function ($event) {
                return config('parameters.event_types')[verifyEventType($event->type)];
            })
            ->editColumn('active', function ($event) {
                return getStatusButton($event->active);
            })
            ->editColumn('room.description', function ($event) {
                return '<a href="' . route('aula.course.onlinelesson.show', $event) . '">' . $event->room->description . '</a>';
            })
            ->rawColumns(['description', 'active', 'room.description'])
            ->make(true);

        return $allEvents;
    }

    // -------------- SECURITY -------------------

    public function getSecurityEventsDatatable(User $user, Course $course)
    {
        $query = Event::whereHas('course', function ($q) use ($course) {
            $q->where('courses.id', $course->id);
        })
            ->whereYear('date', '=', date('Y'))
            ->where('active', 'S')
            ->doesntHave('courseModule')
            ->select('events.*');

        $rawColumns = ['participants'];

        $allEvents = DataTables::of($query)
            ->addColumn('participants', function ($event) {
                return '<a href="' . route('aula.course.events.security.show', $event) . '"> Lista </a>';
            });


        foreach ($user->miningUnits as $miningUnit) {

            if (getMiningUnitSufix($miningUnit->description) == 'P') {

                $allEvents->addColumn('signature_p', function ($event) use ($miningUnit) {
                    $button = '<i class="fa-solid fa-circle-check text-success"></i>';

                    if ($event->flg_security_por != 'S') {
                        $button = '<a href="' . route('aula.signatures.security.index', [$event, getMiningUnitSufix($miningUnit->description)]) . '">
                                        Pendiente
                                    </a>';
                    }

                    return $button;
                });

                array_push($rawColumns, 'signature_p');
            }
            if (getMiningUnitSufix($miningUnit->description) == 'A') {
                $allEvents->addColumn('signature_a', function ($event) use ($miningUnit) {
                    $button = '<i class="fa-solid fa-circle-check text-success"></i>';

                    if ($event->flg_security != 'S') {
                        $button = '<a href="' . route('aula.signatures.security.index', [$event, getMiningUnitSufix($miningUnit->description)]) . '">
                                        Pendiente
                                    </a>';
                    }

                    return $button;
                });

                array_push($rawColumns, 'signature_a');
            }
        }

        return $allEvents->rawColumns($rawColumns)
            ->make(true);
    }

    public function storeSignatureSecurity(User $user, $imgBase64, Event $event, $miningUnitSufix, $storage)
    {
        $file_type = 'imagenes';
        $category = 'firmas';
        $belongsTo = 'firmas';

        if (
            ($miningUnitSufix == 'A' && $event->flg_security != 'S') ||
            ($miningUnitSufix == 'P' && $event->flg_security_por != 'S')
        ) {

            if (
                app(FileService::class)->storeSignature(
                    $user,
                    $imgBase64,
                    $file_type,
                    $category,
                    $belongsTo,
                    $storage,
                    $event
                )
            ) {
                foreach ($user->miningUnits as $miningUnit) {

                    if ($miningUnitSufix == 'A') {

                        return $event->update([
                            'flg_security' => 'S',
                            'security_id' => $user->id,
                        ]);
                    }
                    if ($miningUnitSufix == 'P') {

                        return $event->update([
                            'flg_security_por' => 'S',
                            'security_por_id' => $user->id,
                        ]);
                    }
                }

                return false;
            }
        }

        throw new Exception(config('parameters.exception_message'));
    }


    public function informationForCreateEvent()
    {
        $allExams = Exam::withCount('questions')->having('questions_count', '>=', 2)
            ->get(['id', 'title', 'exam_type']);

        $exams = $allExams->where('exam_type', 'dynamic');

        $types_array = array();
        $types = $this->getTypes();
        foreach ($types as $key => $type) {
            array_push($types_array, [
                $key => $type,
            ]);
        }

        $responsables_array = array();
        $responsables = User::getResponsablesQuery()->get(['id', 'name', 'paternal']);
        foreach ($responsables as $responsable) {
            array_push($responsables_array, [
                $responsable->id => $responsable->full_name,
            ]);
        }

        $instructors_array = array();
        $instructors = User::getInstructorsQuery()->get(['id', 'name', 'paternal']);
        foreach ($instructors as $instructor) {
            array_push($instructors_array, [
                $instructor->id => $instructor->full_name,
            ]);
        }

        $rooms_array = array();
        $rooms = Room::get(['id', 'description']);
        foreach ($rooms as $room) {
            array_push($rooms_array, [
                $room->id => $room->description,
            ]);
        }

        $owner_companies_array = array();
        $owner_companies = OwnerCompany::get(['id', 'name']);
        foreach ($owner_companies as $owner_company) {
            array_push($owner_companies_array, [
                $owner_company->id => $owner_company->name,
            ]);
        }

        $exams_array = array();
        foreach ($exams as $exam) {
            array_push($exams_array, [
                $exam->id => $exam->title,
            ]);
        }


        return [
            "type" => $types_array,
            "user_id" => $instructors_array,
            "responsable_id" => $responsables_array,
            "room_id" => $rooms_array,
            "owner_companies_id" => $owner_companies_array,
            "exam_id" => $exams_array,
        ];

    }

    // --------------- SUPERVISOR ------------------

    public function getSuperVisorDataTable(Request $request)
    {
        $query = Event::with([
            'exam.course',
            'user',
            'responsable',
        ])
            ->withCount(['certifications', 'userSurveys'])
            ->doesntHave('courseModule');

        if ($request->filled('from_date') && $request->filled('end_date')) {
            $query = $query->whereBetween('date', [$request->from_date, $request->end_date]);
        }

        if ($request->filled('search_course')) {
            $query = $query->whereHas('exam.course', function ($query) use ($request) {
                $query->where('id', $request['search_course']);
            });
        }

        if ($request->filled('search_instructor')) {
            $query = $query->where('user_id', $request['search_instructor']);
        }

        if ($request->filled('search_responsable')) {
            $query = $query->where('responsable_id', $request['search_responsable']);
        }

        $allEvents = DataTables::of($query)
            ->editColumn('description', function ($event) {
                return '<a href="' . route('aula.supervisor.events.show', $event) . '">' . $event->description . '</a>';
            })
            ->editColumn('type', function ($event) {
                return config('parameters.event_types')[verifyEventType($event->type)];
            })
            ->editColumn('user.name', function ($event) {
                $user = $event->user;
                return $user->full_name;
            })
            ->editColumn('responsable.name', function ($event) {
                $responsable = $event->responsable;
                return $responsable->full_name;
            })
            ->editColumn('flg_asist', function ($event) {
                return $event->flg_asist == 'S' ? 'Habilitado' : 'Deshabilitado';
            })
            ->editColumn('active', function ($event) {
                $status = $event->active;
                $statusBtn = '<span class="status ' . getStatusClass($status) . '">' . getStatusText($status) . '</span>';

                return $statusBtn;
            })
            ->rawColumns(['description', 'active'])
            ->make(true);

        return $allEvents;
    }



    // --------------- SPEC COURSES -----------------

    public function getSpecCourseDataTable(int $module_id, int $group_event_id)
    {
        $query = Event::with([
            'exam.course',
            'user',
            'responsable',
        ])
            ->withCount(['certifications', 'userSurveys'])
            ->has('courseModule')
            ->where('course_module_id', $module_id)
            ->where('group_event_id', $group_event_id);

        $allEvents = DataTables::of($query)
            ->editColumn('description', function ($event) {
                return '<a href="' . route('admin.specCourses.events.show', $event) . '">' . $event->description . '</a>';
            })
            ->editColumn('type', function ($event) {
                return config('parameters.event_types')[verifyEventType($event->type)];
            })
            ->editColumn('user.name', function ($event) {
                return $event->user->full_name;
            })
            ->editColumn('responsable.name', function ($event) {
                return $event->responsable->full_name;
            })
            ->editColumn('flg_asist', function ($event) {
                return $event->flg_asist == 'S' ? 'Habilitado' : 'Deshabilitado';
            })
            ->editColumn('active', function ($event) {
                return getStatusButton($event->active);
            })
            ->addColumn('action', function ($event) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $event->id . '" data-url="' . route('admin.specCourses.events.update', $event) . '"
                                    data-send="' . route('admin.specCourses.events.edit', $event) . '"
                                    data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                    editSpecEvent-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $event->certifications_count == 0 &&
                    $event->user_surveys_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $event->id . '" data-original-title="delete"
                                        data-place="index"
                                        data-url="' . route('admin.specCourses.events.destroy', $event) . '" class="ms-3 edit btn btn-danger btn-sm
                                        deleteSpecEvent-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['description', 'active', 'action'])
            ->make(true);

        return $allEvents;
    }

    public function specCourseStore($request, CourseModule $module, GroupEvent $groupEvent)
    {
        $data = normalizeInputStatus($request->validated());

        $data['course_module_id'] = $module->id;
        $data['group_event_id'] = $groupEvent->id;

        $event = Event::create($data);

        if ($event) {
            return $event;
        }

        throw new Exception(config('parameters.exception_message'));
    }



    // --------------- FREE COURSE LIVE -----------------

    public function getEventsFreeCourseLiveDataTable(Course $course)
    {

        $query = $course->events()
            ->with([
                'exam.course',
                'user',
                'responsable',
            ])
            ->withCount(['certifications', 'userSurveys']);
        // ->has('courseModule');
        // ->where('course_module_id', $module_id);
        // ->where('group_event_id', $group_event_id);

        $allEvents = DataTables::of($query)
            ->editColumn('description', function ($event) {
                return '<a href="' . route('admin.specCourses.events.show', $event) . '">' . $event->description . '</a>';
            })
            ->editColumn('type', function ($event) {
                return config('parameters.event_types')[verifyEventType($event->type)];
            })
            ->editColumn('user.name', function ($event) {
                return $event->user->full_name;
            })
            ->editColumn('responsible.name', function ($event) {
                return $event->responsable->full_name;
            })
            ->editColumn('flg_assist', function ($event) {
                return $event->flg_asist == 'S' ? 'Habilitado' : 'Deshabilitado';
            })
            ->editColumn('active', function ($event) {
                return getStatusButton($event->active);
            })
            ->addColumn('action', function ($event) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $event->id . '" data-url="' . route('admin.specCourses.events.update', $event) . '"
                                    data-send="' . route('admin.specCourses.events.edit', $event) . '"
                                    data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                    editSpecEvent-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $event->certifications_count == 0 &&
                    $event->user_surveys_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $event->id . '" data-original-title="delete"
                                        data-place="index"
                                        data-url="' . route('admin.specCourses.events.destroy', $event) . '" class="ms-3 edit btn btn-danger btn-sm
                                        deleteSpecEvent-btn"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['description', 'active', 'action'])
            ->make(true);

        return $allEvents;
    }


}
