<?php

namespace Database\Seeders;

use App\Models\Barcode;
use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\Shift;
use Database\Factories\DivisionFactory;
use Database\Factories\EducationFactory;
use Database\Factories\JobTitleFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
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

        (new AdminSeeder)->run();

        // 3 QR Code lokasi di area Jakarta Pusat
        $locations = [
            ['name' => 'Kantor Pusat Jakarta', 'value' => 'MAZ-HQ-JAKARTA', 'lat' => -6.2088, 'lng' => 106.8456, 'radius' => 100],
            ['name' => 'Ruang Meeting Utama', 'value' => 'MAZ-MEETING-01', 'lat' => -6.2089, 'lng' => 106.8457, 'radius' => 30],
            ['name' => 'Cabang Kemang', 'value' => 'MAZ-CABANG-KMG', 'lat' => -6.2633, 'lng' => 106.8133, 'radius' => 75],
        ];
        foreach ($locations as $loc) {
            Barcode::firstOrCreate(
                ['value' => $loc['value']],
                [
                    'name' => $loc['name'],
                    'latitude' => $loc['lat'],
                    'longitude' => $loc['lng'],
                    'radius' => $loc['radius'],
                ]
            );
        }

        if (!Shift::exists()) {
            Shift::factory(2)->create();
        }

        // Data dummy lengkap untuk presentasi / screenshot
        (new EmployeeSeeder)->run();
        (new AttendanceSeeder)->run();
        (new LeaveRequestSeeder)->run();
    }
}
