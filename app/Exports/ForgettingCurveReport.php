<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ForgettingCurveReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $forgettingCurves;

    public function view(): View
    {
        return view('admin.forgettingCurve.export.forgetting_curve_export', [
            'forgettingCurves' => $this->forgettingCurves
        ]);
    }

    public function setForgettingCurves($forgettingCurves)
    {
        $this->forgettingCurves = $forgettingCurves;
    }
}
