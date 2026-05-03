<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Barcode;
use App\Models\Setting;
use Ballen\Distical\Calculator as DistanceCalculator;
use Ballen\Distical\Entities\LatLong;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function scan()
    {
        $today = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        $locations = Barcode::select('id', 'name', 'value', 'latitude', 'longitude', 'radius')->get();

        return view('attendances.scan', [
            'today' => $today,
            'locations' => $locations,
            'workStart' => Setting::get('work_start', config('presensi.work_start')),
            'workEnd' => Setting::get('work_end', config('presensi.work_end')),
            'tolerance' => (int) Setting::get('late_tolerance', config('presensi.late_tolerance')),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'qr_value' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $barcode = Barcode::firstWhere('value', $data['qr_value']);
        if (!$barcode) {
            return back()->with('flash.banner', 'QR Code tidak dikenali.')
                ->with('flash.bannerStyle', 'danger');
        }

        if ($barcode->radius > 0) {
            $dist = $this->distanceMeters(
                $data['latitude'], $data['longitude'],
                $barcode->latitude, $barcode->longitude
            );
            if ($dist > $barcode->radius) {
                return back()->with('flash.banner', "Lokasi Anda di luar radius kantor ({$dist}m / max {$barcode->radius}m).")
                    ->with('flash.bannerStyle', 'danger');
            }
        }

        $now = Carbon::now();
        $existing = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $now->toDateString())
            ->first();

        if ($existing) {
            if ($existing->time_out) {
                return back()->with('flash.banner', 'Anda sudah menyelesaikan presensi hari ini.')
                    ->with('flash.bannerStyle', 'warning');
            }
            $existing->update(['time_out' => $now->format('H:i:s')]);
            $message = 'Presensi pulang berhasil dicatat pukul '.$now->format('H:i').'.';
        } else {
            $workStart = Setting::get('work_start', config('presensi.work_start'));
            $tolerance = (int) Setting::get('late_tolerance', config('presensi.late_tolerance'));
            $cutoff = Carbon::createFromTimeString($workStart)->addMinutes($tolerance);
            $status = $now->gt($cutoff) ? 'late' : 'present';

            Attendance::create([
                'user_id' => Auth::id(),
                'barcode_id' => $barcode->id,
                'date' => $now->toDateString(),
                'time_in' => $now->format('H:i:s'),
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'status' => $status,
            ]);

            $message = $status === 'late'
                ? 'Presensi masuk dicatat pukul '.$now->format('H:i').' (Terlambat).'
                : 'Presensi masuk berhasil dicatat pukul '.$now->format('H:i').'.';
        }

        Attendance::clearUserAttendanceCache(Auth::user(), $now);

        return redirect()->route('home')->with('flash.banner', $message);
    }

    private function distanceMeters(float $lat1, float $lng1, float $lat2, float $lng2): int
    {
        $calc = new DistanceCalculator(new LatLong($lat1, $lng1), new LatLong($lat2, $lng2));
        return (int) floor($calc->get()->asKilometres() * 1000);
    }
}
