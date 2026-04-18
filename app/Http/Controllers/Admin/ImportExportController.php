<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendancesExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\AttendancesImport;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    public function index()
    {
        return view('admin.import-export.index');
    }

    public function users()
    {
        return $this->index();
    }

    public function attendances()
    {
        return $this->index();
    }

    public function exportUsers(Request $request)
    {
        $roles = $request->input('roles', [User::ROLE_KARYAWAN]);
        return Excel::download(new UsersExport($roles), 'karyawan-'.now()->format('Ymd').'.xlsx');
    }

    public function exportAttendances(Request $request)
    {
        return Excel::download(
            new AttendancesExport(
                date: $request->date,
                month: $request->month,
                week: $request->week,
            ),
            'presensi-'.now()->format('Ymd').'.xlsx'
        );
    }

    public function importUsers(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,xls,xlsx,ods']);
        Excel::import(new UsersImport, $request->file('file'));
        return redirect()->route('admin.import-export.users')
            ->with('flash.banner', 'Data karyawan berhasil diimpor.');
    }

    public function importAttendances(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,xls,xlsx,ods']);
        Excel::import(new AttendancesImport, $request->file('file'));
        return redirect()->route('admin.import-export.attendances')
            ->with('flash.banner', 'Data presensi berhasil diimpor.');
    }
}
