<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ParticipantsScoreImportTemplate implements FromView, ShouldAutoSize
{
    use Exportable;

    public function view(): View
    {
        return view('admin.events.exports._participants_score_template');
    }
}
