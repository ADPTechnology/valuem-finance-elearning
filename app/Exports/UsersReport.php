<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersReport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $users;

    public function view(): View
    {
        return view('admin.users.exports._users_export', [
            'users' => $this->users
        ]);
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }
}
