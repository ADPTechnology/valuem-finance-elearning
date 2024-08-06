<?php

namespace App\Services;

use App\Models\{CourseModule, Event, SectionChapter, SpecCourse};
use Auth;
use Datatables;
use Exception;
use Illuminate\Http\Request;


class CourseModuleService
{
    public function store($request, SpecCourse $specCourse)
    {
        $data = normalizeInputStatus($request->validated());

        $lastOrder = $specCourse->getModulesLastOrder();

        if (
            $courseModule = $specCourse->modules()->create($data + [
                "order" => $lastOrder + 1,
            ])
        ) {
            return $courseModule;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, CourseModule $module)
    {
        $data = normalizeInputStatus($request->validated());

        if ($this->updateOrder($data['order'], $module)) {
            return $module->update($data);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function updateOrder($order, CourseModule $module)
    {
        if ($module->order != $order) {

            if (
                CourseModule::where('spec_course_id', $module->spec_course_id)
                    ->where('order', $order)
                    ->update([
                        "order" => $module->order,
                    ])
            ) {
                return $module->update([
                    "order" => $order
                ]);
            } else {
                return false;
            }
        }

        return true;
    }

    public function destroy(CourseModule $module, SpecCourse $specCourse, $storage)
    {
        if ($files = $module->files) {

            foreach ($files as $file) {
                app(FileService::class)->destroy($file, $storage);
            }
        }

        if ($module->delete()) {
            $this->updateAllOrders($specCourse);
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    private function updateAllOrders(SpecCourse $specCourse)
    {
        $modules = $specCourse->modules()->orderBy('order', 'ASC')->get();

        $order = 1;
        foreach ($modules as $module) {
            $module->update([
                "order" => $order,
            ]);
            $order++;
        }
    }

    public function storeFiles(CourseModule $module, $request, $storage)
    {
        $file_type = 'archivos';
        $category = 'modulos';
        $belongsTo = 'modulos';
        $relation = 'one_many';

        foreach ($request['files'] as $file) {

            app(FileService::class)->store(
                $module,
                $file_type,
                $category,
                $file,
                $storage,
                $belongsTo,
                $relation
            );
        }

        return true;
    }



    // ------------ AULA --------------


    public function getDataTable(SpecCourse $specCourse)
    {
        $user = Auth::user();

        $query = $specCourse->modules()
            ->whereHas('events', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('active', 'S');
            })
            ->withCount([
                'events' => function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                }
            ])
            ->where('active', 'S');

        $allModules = Datatables::of($query)
            ->editColumn('title', function ($module) {
                return '<a href="' . route('aula.specCourses.modules.show', $module) . '">' . $module->title . '</a>';
            })
            ->addColumn('details', function ($module) {
                return $module->events_count . ' - Eventos asignados';
            })
            ->rawColumns(['title'])
            ->make(true);

        return $allModules;
    }

    public function getEventsDataTable(CourseModule $module)
    {
        $user = Auth::user();

        $query = $module->events()
            ->whereHas('courseModule', function ($q) {
                $q->where('course_modules.active', 'S');
            })
            ->where('user_id', $user->id)
            ->where('events.active', 'S')
            ->with([
                'room',
                'courseModule.specCourse'
            ])
            ->select('events.*');

        $allEvents = DataTables::of($query)
            ->editColumn('description', function ($event) {
                return '<a href="' . route('aula.specCourses.modules.showParticipants', $event) . '">' . $event->description . '</a>';
            })
            ->editColumn('type', function ($event) {
                return config('parameters.event_types')[verifyEventType($event->type)] ?? '-';
            })
            ->editColumn('active', function ($event) {
                return getStatusButton($event->active);
            })
            ->editColumn('room.description', function ($event) {
                return '<a href="' . route('aula.specCourses.onlinelesson.show', $event) . '">' . $event->room->description . '</a>';
            })
            ->rawColumns(['description', 'active', 'room.description'])
            ->make(true);

        return $allEvents;
    }

    public function getParticipantsDataTable(Event $event)
    {
        $user = Auth::user();

        return app(CertificationService::class)->getInsParticipantsDatatable($user, $event);
    }
}
