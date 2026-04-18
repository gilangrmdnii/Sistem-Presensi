<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();
        $totalKaryawan = User::where('role', User::ROLE_KARYAWAN)->count();

        $todayAttendances = Attendance::whereDate('date', $today)->get();
        $presentCount = $todayAttendances->whereIn('status', ['present', 'late'])->count();
        $lateCount = $todayAttendances->where('status', 'late')->count();
        $leaveCount = $todayAttendances->whereIn('status', ['excused', 'sick'])->count();
        $absentCount = max(0, $totalKaryawan - $presentCount - $leaveCount);

        $pendingLeaves = LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count();

        // 7-day trend
        $trend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            $count = Attendance::whereDate('date', $date)
                ->whereIn('status', ['present', 'late'])
                ->count();
            return [
                'label' => $date->translatedFormat('D d/m'),
                'count' => $count,
            ];
        });

        $recentLeaves = LeaveRequest::with('user')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalKaryawan', 'presentCount', 'lateCount', 'leaveCount', 'absentCount',
            'pendingLeaves', 'trend', 'recentLeaves'
        ));
    }
}
