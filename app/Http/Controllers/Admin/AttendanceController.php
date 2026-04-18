<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Division;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', today()->toDateString());
        $divisionId = $request->query('division');

        $attendances = Attendance::with('user.division')
            ->whereDate('date', $date)
            ->when($divisionId, fn ($q) => $q->whereHas('user', fn ($qq) => $qq->where('division_id', $divisionId)))
            ->get()
            ->keyBy('user_id');

        $employees = User::where('role', User::ROLE_KARYAWAN)
            ->with('division')
            ->when($divisionId, fn ($q) => $q->where('division_id', $divisionId))
            ->orderBy('name')
            ->get()
            ->map(function ($u) use ($attendances) {
                $u->setAttribute('today_attendance', $attendances->get($u->id));
                return $u;
            });

        $divisions = Division::orderBy('name')->get();

        return view('admin.attendances.index', compact('employees', 'date', 'divisionId', 'divisions'));
    }

    public function report(Request $request)
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
            'division' => ['nullable', 'exists:divisions,id'],
        ]);

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $rows = Attendance::with('user.division')
            ->whereBetween('date', [$start, $end])
            ->when($request->division, fn ($q) => $q->whereHas('user', fn ($qq) => $qq->where('division_id', $request->division)))
            ->orderBy('date')
            ->orderBy('user_id')
            ->get();

        $pdf = Pdf::loadView('admin.attendances.report', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("presensi-{$start->toDateString()}-{$end->toDateString()}.pdf");
    }
}
