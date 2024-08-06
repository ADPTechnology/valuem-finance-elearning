<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CourseReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $courses;

    public function view(): View
    {
        return view('admin.courses.export.courses_export', [
            'courses' => $this->courses
        ]);
    }

    public function setCourses($courses)
    {
        $this->courses = $courses;
    }
}
