<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExamsReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $exams;

    public function view(): View
    {
        return view('admin.exams.export.exams_export', [
            'exams' => $this->exams
        ]);
    }

    public function setExams($exams)
    {
        $this->exams = $exams;
    }
}
