<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FreeCourseLiveReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $freeCoursesLive;

    public function view(): View
    {
        return view('admin.live-free-courses.export.free_course_live_export', [
            'freeCoursesLive' => $this->freeCoursesLive
        ]);
    }

    public function setFreeCourseLive($freeCoursesLive)
    {
        $this->freeCoursesLive = $freeCoursesLive;
    }
}
