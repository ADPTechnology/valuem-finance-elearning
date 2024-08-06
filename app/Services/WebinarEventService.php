<?php

namespace App\Services;

use App\Models\{Webinar, WebinarEvent};
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class WebinarEventService
{
    public function getDataTable(Webinar $webinar)
    {
        $query = $webinar->events()->with(['webinar:id,title', 'instructor'])
                                    ->withCount('certifications');

        return datatables()->of($query)
            ->editColumn('description', function ($webinarEvent) {
                return '<a href="'. route('admin.webinars.all.events.show', $webinarEvent) .'">' .
                        $webinarEvent->description
                        . '</a>';
            })
            ->editColumn('instructor.name', function ($event) {
                return $event->instructor->full_name;
            })
            ->editColumn('time_start', function ($event) {
                return getTimeforHummans($event->time_start);
            })
            ->editColumn('time_end', function ($event) {
                return getTimeforHummans($event->time_start);
            })
            ->editColumn('active', function ($event) {
                return getStatusButton($event->active);
            })
            ->addColumn('action', function ($webinarEvent) {
                $btn = '
                <button id="container-btn-edit" data-toggle="modal" data-id="' . $webinarEvent->id . '"
                    data-url="'. route('admin.webinars.all.events.update', $webinarEvent) .'"
                    data-send="'. route('admin.webinars.all.events.edit', $webinarEvent) .'"
                    data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                    editWebinarEvent">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>';

                if (
                    $webinarEvent->certifications_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $webinarEvent->id . '" data-original-title="delete"
                                    data-place="index"
                                    data-url="'. route('admin.webinars.all.events.destroy', $webinarEvent) .'" class="ms-3 delete btn btn-danger btn-sm
                                    deleteWebinarEvent"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['description', 'active', 'action'])
            ->make(true);
    }

    public function store($request, Webinar $webinar, $storage)
    {
        $data = normalizeInputStatus($request->validated());
        $data['time_start'] = Carbon::createFromFormat('g:i A', $data['time_start'])->format('H:i:s');
        $data['time_end'] = Carbon::createFromFormat('g:i A', $data['time_end'])->format('H:i:s');

        if ($webinarEvent = $webinar->events()->create($data)) {

            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'cursos';
                $belongsTo = 'cursos';
                $relation = 'one_one';

                $file = $request->file('image');

                app(FileService::class)->store(
                    $webinarEvent,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $webinarEvent;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, WebinarEvent $webinarEvent, $storage)
    {
        $data = normalizeInputStatus($request->validated());
        $data['time_start'] = Carbon::createFromFormat('g:i A', $data['time_start'])->format('H:i:s');
        $data['time_end'] = Carbon::createFromFormat('g:i A', $data['time_end'])->format('H:i:s');

        if ($webinarEvent->update($data)) {

            app(FileService::class)->destroy($webinarEvent->file, $storage);

            if ($request->hasFile('image')) {
                $file_type = 'imagenes';
                $category = 'cursos';
                $belongsTo = 'cursos';
                $relation = 'one_one';

                $file = $request->file('image');

                app(FileService::class)->store(
                    $webinarEvent,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $webinarEvent;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(WebinarEvent $webinarEvent, $storage)
    {
        if ($file = $webinarEvent->file) {
            app(FileService::class)->destroy($file, $storage);
        }

        return $webinarEvent->delete();
    }

    public function getParticipantsDataTable(WebinarEvent $webinarEvent)
    {
        $user = Auth::user();

        return app(CertificationService::class)->getWebinarParticipantsDataTable($user, $webinarEvent);
    }


}
