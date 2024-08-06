<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CourseTypeReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $courseTypes;

    public function view(): View
    {
        return view('admin.courseTypes.export.course-types_export', [
            'courseTypes' => $this->courseTypes
        ]);
    }

    public function setCourseTypes($courseTypes)
    {
        $this->courseTypes = $courseTypes;
    }
}
