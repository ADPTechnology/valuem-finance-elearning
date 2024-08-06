<?php

namespace App\Services;

use App\Models\{Webinar};
use Auth;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WebinarService
{
    public function getDataTable()
    {
        $query = Webinar::withCount([
            'files' => fn($q) =>
                $q->where('file_type', 'archivos'),
            'events',
        ]);

        $allWebinars = DataTables::of($query)
            ->editColumn('title', function ($webinar) {
                return '<a href="' . route('admin.webinars.all.show', $webinar) . '">' . $webinar->title . '</a>';
            })
            ->editColumn('description', function ($webinar) {
                return $webinar->description ?? '-';
            })
            ->editColumn('active', function ($webinar) {
                return getStatusButton($webinar->active);
            })
            ->addColumn('action', function ($webinar) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $webinar->id . '" data-url="' . route('admin.webinars.all.update', $webinar) . '"
                                        data-send="' . route('admin.webinars.all.edit', $webinar) . '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editWebinar"><i class="fa-solid fa-pen-to-square"></i></button>';
                if (
                    $webinar->events_count == 0 &&
                    $webinar->files_count == 0
                ) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $webinar->id . '" data-original-title="delete"
                                            data-place="index"
                                            data-url="' . route('admin.webinars.all.destroy', $webinar) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteWebinar"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'active', 'action'])
            ->make(true);

        return $allWebinars;
    }

    public function store($request, $storage)
    {
        $data = normalizeInputStatus($request->validated());

        if ($webinar = Webinar::create($data)) {

            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'webinar';
                $belongsTo = 'webinar';
                $relation = 'one_one';

                $file = $request->file('image');

                app(FileService::class)->store(
                    $webinar,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $webinar;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, Webinar $webinar, $storage)
    {
        $data = normalizeInputStatus($request->validated());

        if ($webinar->update($data)) {

            app(FileService::class)->destroy($webinar->file, $storage);

            if ($request->hasFile('image')) {
                $file_type = 'imagenes';
                $category = 'cursos';
                $belongsTo = 'cursos';
                $relation = 'one_one';

                $file = $request->file('image');

                app(FileService::class)->store(
                    $webinar,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $webinar;

        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(Webinar $webinar, $storage)
    {
        if ($webinar->file) {
            app(FileService::class)->destroy($webinar->file, $storage);
        }

        return $webinar->delete();
    }



    // ----------- FILES ------------


    public function getFilesDataTable(Webinar $webinar)
    {
        $query = $webinar->files()->where('file_type', 'archivos');

        return DataTables::of($query)
            ->editColumn('file_path', function ($file) {
                return '<a href="' . route('admin.webinars.all.files.download', $file) . '">' .
                    $file->name
                    . '</a> ';
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
            ->addColumn('action', function ($file) use ($webinar) {

                $btn = '<a href="javascript:void(0)" data-id="' .
                    $file->id . '" data-original-title="delete"
                                    data-url="' . route('admin.webinars.all.files.destroy', [$webinar, $file]) . '" class="ms-3 edit btn btn-danger btn-sm
                                    deleteFile"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->rawColumns(['file_path', 'action'])
            ->make(true);
    }

    public function storeFiles($request, Webinar $webinar, $storage)
    {
        $file_type = 'archivos';
        $category = 'cursos';
        $belongsTo = 'cursos';
        $relation = 'one_many';

        foreach ($request->file('files') as $file) {

            $stored = app(FileService::class)->store(
                $webinar,
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




    // ----------------- AULA -----------------

    public function getWebinars()
    {

        $user = Auth::user();

        $query = Webinar::where([
            'active' => 'S'
        ])
            ->with([
                'file' => fn($query) =>
                    $query->where('file_type', 'imagenes')
            ])
            ->withCount([
                'events as participants_count' => function ($query) {
                    $query->withCount('certifications');
                }
            ])
            ->withMax('events', 'date');

        if ($user->role == 'instructor') {

            $query
                ->with([
                    'events' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }
                ])
                ->whereHas('events', function ($q) use ($user) {
                    $q->where([
                        'active' => 'S',
                        'user_id' => $user->id
                    ]);
                });

        }

        if ($user->role == 'participants') {

            $query
                ->with(['events' => fn ($q) =>
                    $q->with([
                        'certifications' => fn ($q) =>
                        $q->where('user_id', $user->id)
                            ->with('event:id,date,webinar_id,user_id')
                    ])
                ])
                ->withCount([
                    'events as total_events_count' => function ($query) use ($user) {
                        $query->whereHas('certifications', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        });
                    },
                    'events as completed_events_count' => function ($query) use ($user) {
                        $query->whereHas('certifications', function ($query) use ($user) {
                            $query->where('user_id', $user->id)
                                ->where('unlock_cert', 'S');
                        });
                    }
                ])
                ->whereHas('events', function ($q) use ($user) {
                    $q->where('active', 'S')
                        ->whereHas('certifications', function ($q1) use ($user) {
                            $q1->where('user_id', $user->id);
                        });
                });

        }

        return $query->get();

    }



}
