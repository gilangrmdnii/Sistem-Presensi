<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromView;

class AttendancesExport implements FromView
{
    public function __construct(
        private $date = null,
        private $week = null,
        private $month = null,
        private $year = null,
        private $division = null,
        private $jobTitle = null,
        private $education = null
    ) {
    }

    public function view(): View
    {
        $attendances = Attendance::with('user.division')->filter(
            date: $this->date,
            week: $this->week,
            month: $this->month,
            year: $this->year,
            division: $this->division,
            jobTitle: $this->jobTitle,
            education: $this->education
        )->orderBy('date')->get();

        return view('admin.import-export.export-attendances', ['attendances' => $attendances]);
    }
}
