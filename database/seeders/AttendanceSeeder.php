<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Barcode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::now()->subDays(30);
        $end = Carbon::now();

        $karyawan = User::where('role', User::ROLE_KARYAWAN)->get();
        $barcodes = Barcode::all();
        if ($karyawan->isEmpty() || $barcodes->isEmpty()) {
            return;
        }

        foreach ($start->range($end) as $date) {
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($karyawan as $user) {
                // 82% hadir, 8% terlambat, 4% izin, 3% sakit, 3% alpha
                $roll = mt_rand(1, 100);
                $status = match (true) {
                    $roll <= 82 => 'present',
                    $roll <= 90 => 'late',
                    $roll <= 94 => 'excused',
                    $roll <= 97 => 'sick',
                    default => 'absent',
                };

                // Hari ini — bikin realistic (hanya sebagian udah check-in, belum check-out)
                $isToday = $date->isSameDay(Carbon::today());

                $barcode = $barcodes->random();
                $attr = [
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'status' => $status,
                ];

                if ($status === 'present' || $status === 'late') {
                    $timeIn = $status === 'late'
                        ? Carbon::createFromTime(8, mt_rand(16, 45), mt_rand(0, 59))
                        : Carbon::createFromTime(7, mt_rand(30, 59), mt_rand(0, 59));

                    $attr['barcode_id'] = $barcode->id;
                    $attr['time_in'] = $timeIn->format('H:i:s');
                    $attr['latitude'] = $barcode->latitude + (mt_rand(-10, 10) / 100000);
                    $attr['longitude'] = $barcode->longitude + (mt_rand(-10, 10) / 100000);

                    // Today: ~40% belum check-out
                    if (!$isToday || mt_rand(1, 100) > 40) {
                        $attr['time_out'] = Carbon::createFromTime(17, mt_rand(0, 45), mt_rand(0, 59))->format('H:i:s');
                    }
                } elseif ($status === 'excused') {
                    $attr['note'] = 'Izin keperluan keluarga';
                } elseif ($status === 'sick') {
                    $attr['note'] = 'Sakit, istirahat di rumah';
                }

                Attendance::create($attr);
            }
        }
    }
}
