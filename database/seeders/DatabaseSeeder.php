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

        if (!Barcode::exists()) {
            Barcode::factory(1)->create(['name' => 'QR Code Kantor Pusat']);
        }
        if (!Shift::exists()) {
            Shift::factory(2)->create();
        }
    }
}
