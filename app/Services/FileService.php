<?php

namespace App\Services;

use App\Models\{Certification, Event, File, User};
use Datatables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class FileService
{
    public function store($model, $file_type, $category, $file, $storage, $belongsTo, $relation)
    {
        $directory = $this->makeDirectory($model, $file_type, $category, $belongsTo);
        $filename = $this->getFileName($directory, $file, $storage);

        $file_data = $this->storeInStorage($directory, $filename, $file, $storage);

        $stored_file = $this->getStoredFile($file_data[0], $file_data[1], $file_type, $category);

        if ($relation == 'one_one') {
            return $model->file()->save($stored_file);
        } elseif ($relation == 'one_many') {
            return $model->files()->save($stored_file);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(?File $file, $storage)
    {
        if ($file) {
            if (Storage::disk($storage)->exists($file->file_path)) {
                Storage::disk($storage)->delete($file->file_path);
            }
            return $file->delete();
        }

        return false;
    }

    public function makeDirectory($model, $file_type, $category, $belongsTo)
    {
        $directory = $file_type . '/' . $category;
        if ($belongsTo == 'folder') {
            $directory = $model->folder_path;
        }

        return $directory;
    }

    public function destroyDirectory($directory, $storage)
    {
        return Storage::disk($storage)->deleteDirectory($directory);
    }

    public function storeDirectory($directory, $storage)
    {
        $stored = Storage::disk($storage)->makeDirectory($directory);

        return $stored;
    }

    public function getFileName($directory, $file, $storage)
    {
        $filename = preg_replace('/\s+/', '-', $file->getClientOriginalName());
        $name_array = explode('.', $filename);
        $origin_filename = $name_array[0];
        $extension = $name_array[1];

        $count = 1;
        while (Storage::disk($storage)->exists($directory . '/' . $filename)) {
            $filename = $origin_filename . '(' . $count++ . ').' . $extension;
        }

        return $filename;
    }

    public function storeInStorage($directory, $filename, $file, $storage)
    {
        $file_path = Storage::disk($storage)->putFileAs($directory, $file, $filename);
        $file_url = Storage::disk($storage)->url($file_path);

        return array($file_path, $file_url);
    }

    private function getStoredFile($file_path, $file_url, $file_type, $category)
    {
        $stored_file = new File([
            "file_path" => $file_path,
            "file_url" => $file_url,
            "file_type" => $file_type,
            "category" => $category,
        ]);

        return $stored_file;
    }

    public function validateDownload(File $file, $storage)
    {
        return Storage::disk($storage)->exists($file->file_path);
    }

    public function download($file, $storage)
    {
        if (Storage::disk($storage)->exists($file->file_path)) {
            return Storage::disk($storage)->download($file->file_path);
        }

        return false;
    }

    public function storeSignature(User $user, $imgBase64, $file_type, $category, $belongsTo, $storage, $event = null)
    {
        $directory = $this->makeDirectory($user, $file_type, $category, $belongsTo);
        $image = str_replace('data:image/png;base64,', '', $imgBase64);
        $image = str_replace(' ', '+', $image);
        $image_name = $this->getSignatureImgName($user, $event);
        $full_path = $directory . '/' . $image_name;

        if (Storage::disk($storage)->put($full_path, base64_decode($image))) {

            $files = $user->files()->where('category', 'firmas')->get();

            if ($files->isNotEmpty() && !$event) {
                foreach ($files as $file) {
                    $this->destroy($file, $storage);
                }
            }

            $file_url = Storage::disk($storage)->url($full_path);
            $file = $this->getStoredFile($full_path, $file_url, $file_type, $category);

            if($user->file()->save($file)){
                return $user->update(['signature' => 'S']);
            };
        }

        return false;
    }

    private function getSignatureImgName(User $user, $event)
    {
        $img_name = $user->dni . '_' . time() . '.png';

        if ($event) {
            $img_name = $event->id . '_' . $user->dni . '.png';
        }

        return $img_name;
    }



    // ----------- FILES MANAGEMENT MODULE -------------------


    public function getDatatable(Request $request)
    {
        $query = File::where('file_type', 'archivos')
                    ->whereIn('category', ['certificados', 'asistencias', 'anexos'])
                    ->select('files.*');


        if ($request->filled('from_date') && $request->filled('end_date')) {
            $query = $query->whereBetween('created_at', [$request->from_date, $request->end_date]);
        }

        $allFiles = Datatables::of($query)
            ->editColumn('file_path', function ($file){
                return $file->name;
            })
            ->editColumn('created_at', function ($file) {
                return $file->created_at;
            })
            ->addColumn('action', function ($file) {
                $btn = '<a data-id="' .
                    $file->id . '" href="'. route('admin.filesManagement.download', $file) .'"
                    data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                    downloadFile"><i class="fa-solid fa-download"></i></a>';

                $btn .= '<a href="javascript:void(0)" data-id="' .
                    $file->id . '" data-original-title="delete"
                    data-url="'. route('admin.filesManagement.destroy', $file) .'" class="ms-3 edit btn btn-danger btn-sm
                    deleteFile"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);

        return $allFiles;
    }

    public function storeFile($files, $storage)
    {
        $rejected = [];
        $success = false;
        $found_errors = false;

        foreach ($files as $file) {
            $original_name = $file->getClientOriginalName();
            $original_filename = pathinfo($original_name, PATHINFO_FILENAME);

            try {
                $array_data = Str::of($original_filename)->explode('_');
                $category = $array_data[0];

                if ($category == 'asistencias') // formato: asistencias_codevento.pdf
                {
                    $model = Event::where('id', $array_data[1])->firstOrFail();
                    $belongsTo = 'asistencias';
                }
                elseif ($category == 'anexos') // formato: anexos_dni_codevento.pdf
                {
                    $dni = $array_data[1];
                    $event_id = $array_data[2];

                    $model = Certification::where('event_id', $event_id)
                                            ->whereHas('user', function ($q) use ($dni) {
                                                $q->where('dni', $dni);
                                            })->firstOrFail();
                    $belongsTo = 'anexos';
                }

                if (isset($model)) {
                    $directory = $this->makeDirectory($model, 'archivos', $category, $belongsTo);

                    if (Storage::disk($storage)->exists($directory . '/' . $original_name)) {
                        Storage::disk($storage)->delete($directory . '/' . $original_name);
                        Storage::disk($storage)->putFileAs($directory, $file, $original_name);

                        $success = true;
                    } else {
                        list($file_path, $file_url) = $this->storeInStorage($directory, $original_name, $file, $storage);
                        $stored_file = $this->getStoredFile($file_path, $file_url, 'archivos',  $category);

                        $model->files()->save($stored_file);

                        $success = true;
                    }
                }
                else {
                    array_push($rejected, $original_name);
                    $found_errors = true;
                }

            } catch (Exception $e) {
                array_push($rejected, $original_name);
                $found_errors = true;
            }
        }

        return [$success, $rejected, $found_errors];
    }
}
