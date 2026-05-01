<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $hr = Division::firstOrCreate(['name' => 'Human Resources']);
        $it = Division::firstOrCreate(['name' => 'Teknologi Informasi']);
        $ops = Division::firstOrCreate(['name' => 'Operasional Messaging']);
        $keu = Division::firstOrCreate(['name' => 'Keuangan']);

        $mgr = JobTitle::firstOrCreate(['name' => 'Manager']);
        $spv = JobTitle::firstOrCreate(['name' => 'Supervisor']);
        $staff = JobTitle::firstOrCreate(['name' => 'Staff']);

        // HRD (2 akun)
        User::factory()->hrd()->create([
            'name' => 'Ahmad Nur Aziz',
            'email' => 'hrd@maznusantara.co.id',
            'division_id' => $hr->id,
            'job_title_id' => $mgr->id,
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);
        User::factory()->hrd()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'hrd2@maznusantara.co.id',
            'division_id' => $hr->id,
            'job_title_id' => $staff->id,
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);

        // Atasan Divisi (3 akun - satu per divisi utama)
        User::factory()->atasanDivisi()->create([
            'name' => 'Galih Laksana Abimanyu',
            'email' => 'atasan@maznusantara.co.id',
            'division_id' => $it->id,
            'job_title_id' => $mgr->id,
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);
        User::factory()->atasanDivisi()->create([
            'name' => 'Budi Santoso',
            'email' => 'atasan.ops@maznusantara.co.id',
            'division_id' => $ops->id,
            'job_title_id' => $spv->id,
            'phone' => '081234567893',
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);
        User::factory()->atasanDivisi()->create([
            'name' => 'Dewi Kartika',
            'email' => 'atasan.keu@maznusantara.co.id',
            'division_id' => $keu->id,
            'job_title_id' => $mgr->id,
            'phone' => '081234567894',
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);

        // Karyawan utama (demo akun)
        User::factory()->create([
            'name' => 'Karyawan Demo',
            'email' => 'karyawan@maznusantara.co.id',
            'division_id' => $it->id,
            'job_title_id' => $staff->id,
            'phone' => '081234567895',
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);
    }
}
