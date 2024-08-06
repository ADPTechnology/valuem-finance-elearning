<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExportEvaluations implements FromView, ShouldAutoSize
{
    use Exportable;

    private $evaluationsCompany;
    private $maxColumns;

    public function view() : View
    {
        return view('admin.companies.export.table_evaluations_company', [
            'evaluationsCompany' => $this->evaluationsCompany,
            'maxColumns' => $this->maxColumns,
        ]);
    }

    public function setEvaluations($evaluationsCompany)
    {
        $this->evaluationsCompany = $evaluationsCompany;
        $this->maxColumns = $evaluationsCompany->count();
    }
}
