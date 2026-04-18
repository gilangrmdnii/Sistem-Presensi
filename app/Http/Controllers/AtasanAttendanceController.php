<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtasanAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $atasan = Auth::user();
        $date = $request->query('date', today()->toDateString());

        $karyawan = User::where('role', User::ROLE_KARYAWAN)
            ->where('division_id', $atasan->division_id)
            ->orderBy('name')
            ->get();

        $attendances = Attendance::whereDate('date', $date)
            ->whereIn('user_id', $karyawan->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $karyawan = $karyawan->map(function ($u) use ($attendances) {
            $u->setAttribute('today_attendance', $attendances->get($u->id));
            return $u;
        });

        return view('atasan.attendances', compact('karyawan', 'date'));
    }
}
