<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OwnerCompaniesReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $ownerCompanies;

    public function view(): View
    {
        return view('admin.ownerCompanies.export.owner_companies_export', [
            'ownerCompanies' => $this->ownerCompanies
        ]);
    }

    public function setOwnerCompanies($ownerCompanies)
    {
        $this->ownerCompanies = $ownerCompanies;
    }
}
