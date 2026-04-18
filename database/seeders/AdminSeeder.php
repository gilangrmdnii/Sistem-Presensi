<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $defaultDivision = Division::firstOrCreate(['name' => 'Human Resources']);
        $teknologi = Division::firstOrCreate(['name' => 'Teknologi Informasi']);

        User::factory()->hrd()->create([
            'name' => 'Ahmad Nur Aziz',
            'email' => 'hrd@maznusantara.co.id',
            'division_id' => $defaultDivision->id,
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);

        User::factory()->atasanDivisi()->create([
            'name' => 'Galih Laksana Abimanyu',
            'email' => 'atasan@maznusantara.co.id',
            'division_id' => $teknologi->id,
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'Karyawan MAZ',
            'email' => 'karyawan@maznusantara.co.id',
            'division_id' => $teknologi->id,
            'password' => Hash::make('password'),
            'raw_password' => 'password',
        ]);
    }
}
