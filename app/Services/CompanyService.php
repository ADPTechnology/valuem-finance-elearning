<?php

namespace App\Services;

use App\Models\{Company, File};
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Str;

class CompanyService
{

    // ------------ ADMIN ------------
    public function getDataTable()
    {
        $allCompanies = DataTables::of(Company::withCount('users')
            ->withCount('files'))
            ->addColumn('status-btn', function ($company) {
                $status = $company->active;
                $statusBtn = '<span class="status ' . getStatusClass($status) . '">' .
                    getStatusText($status)
                    . '</span>';

                return $statusBtn;
            })
            ->addColumn('rubric', function ($company) {
                return $company->rubric ?? 'Sin rubro';
            })
            ->addColumn('action', function ($company) {
                $btn = '';
                $btn .= '<button data-toggle="modal" data-id="' .
                    $company->id . '"
                                            data-send="' . route('admin.companies.getDocsContent', $company) . '"
                                            data-url="' . route('admin.companies.publishDocsInCompany', $company) . '"
                                            data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                                            showDocsCompany"><i class="fa-solid fa-file-lines"></i></button>';

                $btn .= '<button data-toggle="modal" data-id="' .
                    $company->id . '" data-url="' . route('admin.companies.update', $company) . '"
                                                data-send="' . route('admin.companies.edit', $company) . '"
                                                data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                                editCompany"><i class="fa-solid fa-pen-to-square"></i></button>';
                if ($company->users_count == 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $company->id . '" data-original-title="delete"
                                            data-url="' . route('admin.companies.delete', $company) . '" class="ms-3 edit btn btn-danger btn-sm
                                            deleteCompany"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['status-btn', 'action'])
            ->make(true);

        return $allCompanies;
    }

    // ------------ USER COMPANY --------------


    public function getDocumentsDataTable(Company $company)
    {
        $query = $company->files()->where('file_type', 'archivos')
            ->whereIn('category', ['empresa']);

        $allFiles = Datatables::of($query)
            ->editColumn('file_path', function ($file) {
                return $file->name;
            })
            ->editColumn('created_at', function ($file) {
                return $file->created_at;
            })
            ->addColumn('action', function ($file) {
                $btn = '<a data-id="' .
                    $file->id . '" href="' . route('aula.docCompany.downloadFile', $file) . '"
            data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
            downloadFile"><i class="fa-solid fa-download"></i></a>';

                $btn .= '<a href="javascript:void(0)" data-id="' .
                    $file->id . '" data-original-title="delete"
            data-url="' . route('aula.docCompany.destroyDocument', $file) . '" class="ms-3 edit btn btn-danger btn-sm
            deleteFile"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);

        return $allFiles;
    }

    public function storeCompanyFiles($files, $company, string $storage)
    {
        $file_type = 'archivos';
        $category = 'empresa';
        $belongsTo = 'empresa';
        $relation = 'one_many';

        $success = [];

        foreach ($files as $file) {

            if (
                app(FileService::class)->store(
                    $company,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                )
            ) {
                array_push($success, $file);
            }
            ;
        }

        return count($files) == count($success);
    }

    public function getEvaluationsCompanyAll(Request $request, Company $company)
    {

        $query = $company->certifications()->with([
            'user',
            'company',
            'event.exam.course.type',
        ])
            ->where('status', 'finished')
            ->where('evaluation_type', 'certification')
            ->select('certifications.*');

        if ($request->filled('status')) {
            if ($request['status'] == 'approved') {
                $query->whereHas('event', function ($q) {
                    $q->whereRaw('certifications.score >= events.min_score');
                });
            } else if ($request['status'] == 'suspended') {
                $query->whereHas('event', function ($q) {
                    $q->whereRaw('certifications.score < events.min_score');
                });
            }
        }

        if ($request->filled('from_date') && $request->filled('end_date')) {
            $query->whereHas('event', function ($q2) use ($request) {
                $q2->whereBetween('date', [$request->from_date, $request->end_date]);
            });
        }

        if ($request->filled('course')) {
            $query->whereHas('event.exam', function ($q3) use ($request) {
                $q3->where('course_id', $request['course']);
            });
        }

        $allEvaluations = DataTables::of($query)
            ->editColumn('certifications.id', function ($certification) {
                return $certification->id;
            })
            ->editColumn('user.name', function ($certification) {
                return $certification->user->full_name_complete;
            })
            ->editColumn('event.description', function ($certification) {
                return
                    $certification->event->description
                ;
            })
            ->editColumn('score', function ($certification) {
                return $certification->score ?? '-';
            })
            ->addColumn('exam', function ($certification) {
                $exam_icon = '<a href="' . route('pdf.examForParticipant', $certification) . '" target="_BLANK">
                                    <img src="' . asset('assets/common/img/exam-icon.svg') . '"
                                    alt="examen-' . $certification->id . '"
                                    style="width:30px;">
                                </a>';

                return $exam_icon;
            })
            ->rawColumns(['exam', 'event.description'])
            ->make(true);

        return $allEvaluations;
    }


    public function getEvaluationsCompany(Request $request, Company $company)
    {

        $fecha = $request->dateRange;
        if ($fecha == 'Todos los registros') {
            $from_date = null;
            $end_date = null;
        } else {
            $partes = explode(" hasta el: ", str_replace("Del: ", "", $fecha));
            $from_date = $partes[0];
            $end_date = $partes[1];
        }

        $query = $company->certifications()->with([
            'user',
            'company',
            'event.exam.course.type',
        ])
            ->where('status', 'finished')
            ->where('evaluation_type', 'certification')
            ->select('certifications.*');

        // if ($request->filled('from_date') && $request->filled('end_date')) {
        //     $query->whereHas('event', function ($q2) use ($request) {
        //         $q2->whereBetween('date', [$request->from_date, $request->end_date]);
        //     });
        // }
        if (!empty($from_date) && !empty($end_date)) {
            $query->whereHas('event', function ($q2) use ($from_date, $end_date) {
                $q2->whereBetween('date', [$from_date, $end_date]);
            });
        }

        if ($request->filled('status')) {
            if ($request['status'] == 'approved') {
                $query->whereHas('event', function ($q) {
                    $q->whereRaw('certifications.score >= events.min_score');
                });
            } else if ($request['status'] == 'suspended') {
                $query->whereHas('event', function ($q) {
                    $q->whereRaw('certifications.score < events.min_score');
                });
            }
        }
        if ($request->filled('course')) {
            $query->whereHas('event.exam', function ($q3) use ($request) {
                $q3->where('course_id', $request['course']);
            });
        }

        return $query->limit(500)->get();

    }

}
