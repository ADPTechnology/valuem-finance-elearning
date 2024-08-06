<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WebinarAllReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $webinars;

    public function view(): View
    {
        return view('admin.webinars.export.webinars_export', [
            'webinars' => $this->webinars
        ]);
    }

    public function setWebinars($webinars)
    {
        $this->webinars = $webinars;
    }
}
