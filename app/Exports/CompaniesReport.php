<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompaniesReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $companies;

    public function view(): View
    {
        return view('admin.companies.export.companies_export', [
            'companies' => $this->companies
        ]);
    }

    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }
}
