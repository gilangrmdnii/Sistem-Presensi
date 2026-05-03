<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'workStart' => Setting::get('work_start', config('presensi.work_start')),
            'workEnd' => Setting::get('work_end', config('presensi.work_end')),
            'lateTolerance' => Setting::get('late_tolerance', config('presensi.late_tolerance')),
            'officeLat' => Setting::get('office_latitude', config('presensi.office.latitude')),
            'officeLng' => Setting::get('office_longitude', config('presensi.office.longitude')),
            'officeRadius' => Setting::get('office_radius', config('presensi.office.radius')),
            'companyName' => Setting::get('company_name', 'PT MAZ Nusantara Cakti'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'work_start' => ['required', 'date_format:H:i'],
            'work_end' => ['required', 'date_format:H:i', 'after:work_start'],
            'late_tolerance' => ['required', 'integer', 'min:0', 'max:120'],
            'office_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'office_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'office_radius' => ['nullable', 'integer', 'min:10', 'max:5000'],
            'company_name' => ['required', 'string', 'max:100'],
        ]);

        foreach ($data as $key => $value) {
            Setting::put($key, (string) $value);
        }

        return redirect()->route('admin.settings')
            ->with('flash.banner', 'Pengaturan berhasil disimpan.');
    }
}
