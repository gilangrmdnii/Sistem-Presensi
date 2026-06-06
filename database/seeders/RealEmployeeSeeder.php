<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Barcode;
use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\Shift;
use App\Models\User;
use Database\Factories\DivisionFactory;
use Database\Factories\EducationFactory;
use Database\Factories\JobTitleFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeder data karyawan riil PT MAZ Nusantara Cakti + absensi 15 hari kerja terakhir.
 *
 * Aman dijalankan berulang (idempotent): karyawan di-key by email,
 * absensi di-key by (user_id, date).
 *
 *   php artisan db:seed --class=RealEmployeeSeeder
 */
class RealEmployeeSeeder extends Seeder
{
    /** Jumlah hari kerja (Senin–Jumat) yang dibuatkan absensinya. */
    private const ACTIVE_WORK_DAYS = 15;

    /**
     * Daftar karyawan. Gender ditebak dari nama; sesuaikan bila perlu.
     */
    private array $karyawan = [
        ['name' => 'Ahsan Hamzawi',          'gender' => 'male'],
        ['name' => 'Rahayu Ismaliana',       'gender' => 'female'],
        ['name' => 'A F Donny Christian',    'gender' => 'male'],
        ['name' => 'Yuliarto Suryaputra',    'gender' => 'male'],
        ['name' => 'Fadhillah Farhan',       'gender' => 'male'],
        ['name' => 'Agung Sulistyo Nugroho', 'gender' => 'male'],
        ['name' => 'Siti Azizah',            'gender' => 'female'],
        ['name' => 'David Indra Permana',    'gender' => 'male'],
        ['name' => 'Galih Laksana Abim',     'gender' => 'male'],
        ['name' => 'Yuspita Astriyanti',     'gender' => 'female'],
        ['name' => 'Surya Azhar K.H',        'gender' => 'male'],
        ['name' => 'Yuli Ristanti',          'gender' => 'female'],
        ['name' => 'Iqbal',                  'gender' => 'male'],
        ['name' => 'Hamzah',                 'gender' => 'male'],
        ['name' => 'Ferdyansyah Noer R',     'gender' => 'male'],
        ['name' => 'Fajriel',                'gender' => 'male'],
        ['name' => 'Tomi',                   'gender' => 'male'],
        ['name' => 'Tuti',                   'gender' => 'female'],
        ['name' => 'Gita',                   'gender' => 'female'],
        ['name' => 'Toni',                   'gender' => 'male'],
        ['name' => 'Hamdan',                 'gender' => 'male'],
        ['name' => 'Mumuh',                  'gender' => 'male'],
        ['name' => 'Ernawati',               'gender' => 'female'],
        ['name' => 'Nik',                    'gender' => 'female'],
        ['name' => 'Muna',                   'gender' => 'female'],
        ['name' => 'Ega',                    'gender' => 'male'],
        ['name' => 'Asep Yusup',             'gender' => 'male'],
        ['name' => 'Yusuf',                  'gender' => 'male'],
        ['name' => 'Muhammad Faiz',          'gender' => 'male'],
        ['name' => 'Sahdulima Yusali',       'gender' => 'male'],
        ['name' => 'Kholipah',               'gender' => 'female'],
        ['name' => 'Ervina Dyah',            'gender' => 'female'],
        ['name' => 'Zaidan Azhari',          'gender' => 'male'],
        ['name' => 'Ahdi',                   'gender' => 'male'],
        ['name' => 'Azizi',                  'gender' => 'male'],
        ['name' => 'Bintang',                'gender' => 'male'],
        ['name' => 'Dafa',                   'gender' => 'male'],
        ['name' => 'Nabil',                  'gender' => 'male'],
        ['name' => 'Yasir',                  'gender' => 'male'],
    ];

    public function run(): void
    {
        $this->ensureMasterData();

        $divisions  = Division::pluck('id')->all();
        $jobTitles  = JobTitle::pluck('id')->all();
        $educations = Education::pluck('id')->all();
        $cities     = ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi'];

        $users = [];

        foreach (array_values($this->karyawan) as $i => $data) {
            $name  = trim($data['name']);
            $email = Str::slug($name, '.') . '@maznusantara.co.id';

            $users[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'nip'          => '2025' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
                    'name'         => $name,
                    'gender'       => $data['gender'],
                    'phone'        => '0812' . fake()->numerify('########'),
                    'division_id'  => $divisions[$i % count($divisions)],
                    'job_title_id' => $jobTitles[$i % count($jobTitles)],
                    'education_id' => fake()->randomElement($educations),
                    'city'         => fake()->randomElement($cities),
                    'address'      => fake()->streetAddress(),
                    'birth_place'  => fake()->randomElement($cities),
                    'birth_date'   => fake()->dateTimeBetween('-45 years', '-22 years')->format('Y-m-d'),
                    'role'         => User::ROLE_KARYAWAN,
                    'status'       => User::STATUS_ACTIVE,
                    'email_verified_at' => now(),
                    'password'     => Hash::make('password'),
                    'raw_password' => 'password',
                ]
            );
        }

        $this->command?->info(count($users) . ' karyawan siap. Membuat absensi ' . self::ACTIVE_WORK_DAYS . ' hari kerja...');

        $this->seedAttendance($users);
    }

    /**
     * Pastikan divisi, pendidikan, jabatan, dan minimal 1 barcode + shift tersedia,
     * sehingga seeder ini bisa dijalankan mandiri tanpa DatabaseSeeder.
     */
    private function ensureMasterData(): void
    {
        foreach (DivisionFactory::$divisions as $value) {
            Division::firstOrCreate(['name' => $value]);
        }
        foreach (EducationFactory::$educations as $value) {
            Education::firstOrCreate(['name' => $value]);
        }
        foreach (JobTitleFactory::$jobTitles as $value) {
            JobTitle::firstOrCreate(['name' => $value]);
        }

        Barcode::firstOrCreate(
            ['value' => 'MAZ-HQ-JAKARTA'],
            ['name' => 'Kantor Pusat Jakarta', 'latitude' => -6.2088, 'longitude' => 106.8456, 'radius' => 100]
        );

        if (! Shift::exists()) {
            Shift::create(['name' => 'Shift Pagi', 'start_time' => '08:00:00', 'end_time' => '17:00:00']);
        }
    }

    /**
     * Buat absensi untuk ACTIVE_WORK_DAYS hari kerja terakhir (Senin–Jumat).
     * Mayoritas hadir, sebagian kecil telat, sesekali izin/sakit — tanpa alpha.
     */
    private function seedAttendance(array $users): void
    {
        $barcodes = Barcode::all();
        $shiftId  = Shift::value('id');

        // Kumpulkan tanggal hari kerja, mundur dari hari ini.
        $workDays = [];
        $cursor   = Carbon::today();
        while (count($workDays) < self::ACTIVE_WORK_DAYS) {
            if (! $cursor->isWeekend()) {
                $workDays[] = $cursor->copy();
            }
            $cursor->subDay();
        }
        $workDays = array_reverse($workDays); // urut dari paling lama ke hari ini

        foreach ($workDays as $date) {
            $isToday = $date->isSameDay(Carbon::today());

            foreach ($users as $user) {
                // 90% hadir, 7% telat, 2% izin, 1% sakit (aktif absen, tanpa alpha).
                $roll   = mt_rand(1, 100);
                $status = match (true) {
                    $roll <= 90 => 'present',
                    $roll <= 97 => 'late',
                    $roll <= 99 => 'excused',
                    default     => 'sick',
                };

                $attr = [
                    'shift_id' => $shiftId,
                    'status'   => $status,
                    'note'     => null,
                    'time_in'  => null,
                    'time_out' => null,
                    'barcode_id' => null,
                    'latitude'   => null,
                    'longitude'  => null,
                ];

                if ($status === 'present' || $status === 'late') {
                    $barcode = $barcodes->random();
                    $timeIn  = $status === 'late'
                        ? Carbon::createFromTime(8, mt_rand(16, 50), mt_rand(0, 59))
                        : Carbon::createFromTime(7, mt_rand(30, 59), mt_rand(0, 59));

                    $attr['barcode_id'] = $barcode->id;
                    $attr['time_in']    = $timeIn->format('H:i:s');
                    $attr['latitude']   = $barcode->latitude + (mt_rand(-10, 10) / 100000);
                    $attr['longitude']  = $barcode->longitude + (mt_rand(-10, 10) / 100000);

                    // Hari ini: ~35% belum check-out (masih kerja).
                    if (! $isToday || mt_rand(1, 100) > 35) {
                        $attr['time_out'] = Carbon::createFromTime(17, mt_rand(0, 50), mt_rand(0, 59))->format('H:i:s');
                    }
                } elseif ($status === 'excused') {
                    $attr['note'] = 'Izin keperluan keluarga';
                } else { // sick
                    $attr['note'] = 'Sakit, istirahat di rumah';
                }

                Attendance::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date->toDateString()],
                    $attr
                );
            }
        }
    }
}
