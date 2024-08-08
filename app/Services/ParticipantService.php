<?php
namespace App\Services;

use App\Models\User;
use Auth;
use Yajra\DataTables\Facades\DataTables;


class ParticipantService
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getDocumentsDataTable()
    {

        $user = Auth::user();
        $query = $user->files()->where('file_type', 'archivos')
            ->whereIn('category', ['participantDoc']);

        $allFiles = DataTables::of($query)
            ->editColumn('file_path', function ($file) {
                return $file->name;
            })
            ->editColumn('created_at', function ($file) {
                return $file->created_at;
            })
            ->addColumn('action', function ($file) {
                $btn = '<a data-id="' .
                    $file->id . '" href="' . route('aula.myDocs.downloadFile', $file) . '"
            data-original-title="edit" class="me-3 edit btn btn-success btn-sm
            downloadFile"><i class="fa-solid fa-download"></i></a>';

                $btn .= '<a href="javascript:void(0)" data-id="' .
                    $file->id . '" data-original-title="delete"
            data-url="' . route('aula.myDocs.destroyDocument', $file) . '" class="ms-3 edit btn btn-primary btn-sm
            deleteFile"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);

        return $allFiles;
    }


    public function storeParticipantFiles($files, User $participant, string $storage)
    {

        $file_type = 'archivos';
        $category = 'participantDoc';
        $belongsTo = 'participantDoc';
        $relation = 'one_many';

        $success = [];

        foreach ($files as $file) {

            if (
                $this->fileService->store(
                    $participant,
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


}
