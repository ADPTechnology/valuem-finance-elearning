<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MiningUnitsReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $miningUnits;

    public function view(): View
    {
        return view('admin.miningUnits.export.mining_units_export', [
            'miningUnits' => $this->miningUnits
        ]);
    }

    public function setMiningUnits($miningUnits)
    {
        $this->miningUnits = $miningUnits;
    }
}
