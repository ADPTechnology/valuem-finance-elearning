<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SpecCourseReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $specCourses;

    public function view(): View
    {
        return view('admin.specCourses.export.spec_courses_export', [
            'specCourses' => $this->specCourses
        ]);
    }

    public function setSpecCourses($specCourses)
    {
        $this->specCourses = $specCourses;
    }
}
