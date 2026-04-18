<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $monthlyStats = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pendingLeaves = LeaveRequest::where('user_id', $user->id)
            ->where('status', LeaveRequest::STATUS_PENDING)
            ->count();

        $recentAttendances = Attendance::where('user_id', $user->id)
            ->latest('date')
            ->limit(7)
            ->get();

        return view('home', compact(
            'todayAttendance',
            'monthlyStats',
            'pendingLeaves',
            'recentAttendances',
        ));
    }
}
