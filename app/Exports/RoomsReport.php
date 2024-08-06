<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RoomsReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $rooms;

    public function view(): View
    {
        return view('admin.rooms.export.rooms_export', [
            'rooms' => $this->rooms
        ]);
    }

    public function setRooms($rooms)
    {
        $this->rooms = $rooms;
    }
}
