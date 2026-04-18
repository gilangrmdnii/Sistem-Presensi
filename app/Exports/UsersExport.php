<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class UsersExport implements FromView
{
    /**
     * @param array<string> $roles
     */
    public function __construct(private array $roles = [User::ROLE_KARYAWAN])
    {
    }

    public function view(): View
    {
        return view('admin.import-export.export-users', [
            'users' => User::whereIn('role', $this->roles)->get(),
        ]);
    }
}
