<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = Division::pluck('id', 'name');
        $jobTitles = JobTitle::pluck('id', 'name');
        $educations = Education::pluck('id', 'name');

        $karyawanList = [
            ['name' => 'Rizky Pratama',       'email' => 'rizky.pratama@maznusantara.co.id',    'division' => 'Teknologi Informasi', 'job' => 'IT Engineer',      'gender' => 'male'],
            ['name' => 'Putri Ayu Lestari',   'email' => 'putri.ayu@maznusantara.co.id',        'division' => 'Teknologi Informasi', 'job' => 'System Analyst',   'gender' => 'female'],
            ['name' => 'Andi Wijaya',         'email' => 'andi.wijaya@maznusantara.co.id',      'division' => 'Teknologi Informasi', 'job' => 'Junior Staff',     'gender' => 'male'],
            ['name' => 'Maya Ramadhani',      'email' => 'maya.ramadhani@maznusantara.co.id',   'division' => 'Operasional Messaging','job' => 'Senior Staff',    'gender' => 'female'],
            ['name' => 'Fajar Nugraha',       'email' => 'fajar.nugraha@maznusantara.co.id',    'division' => 'Operasional Messaging','job' => 'Staff',           'gender' => 'male'],
            ['name' => 'Indah Sari',          'email' => 'indah.sari@maznusantara.co.id',       'division' => 'Operasional Messaging','job' => 'Staff',           'gender' => 'female'],
            ['name' => 'Bagas Setiawan',      'email' => 'bagas.setiawan@maznusantara.co.id',   'division' => 'Keuangan',            'job' => 'Accountant',       'gender' => 'male'],
            ['name' => 'Nadia Putri',         'email' => 'nadia.putri@maznusantara.co.id',      'division' => 'Keuangan',            'job' => 'Junior Staff',     'gender' => 'female'],
            ['name' => 'Reza Firmansyah',     'email' => 'reza.firmansyah@maznusantara.co.id',  'division' => 'Business Development','job' => 'Business Analyst', 'gender' => 'male'],
            ['name' => 'Dian Permata',        'email' => 'dian.permata@maznusantara.co.id',     'division' => 'Business Development','job' => 'Senior Staff',    'gender' => 'female'],
            ['name' => 'Aldi Kurniawan',      'email' => 'aldi.kurniawan@maznusantara.co.id',   'division' => 'Customer Support',    'job' => 'Customer Relation','gender' => 'male'],
            ['name' => 'Sari Melati',         'email' => 'sari.melati@maznusantara.co.id',      'division' => 'Customer Support',    'job' => 'Staff',            'gender' => 'female'],
            ['name' => 'Doni Hermawan',       'email' => 'doni.hermawan@maznusantara.co.id',    'division' => 'Customer Support',    'job' => 'Junior Staff',     'gender' => 'male'],
            ['name' => 'Linda Kusuma',        'email' => 'linda.kusuma@maznusantara.co.id',     'division' => 'Human Resources',     'job' => 'Staff',            'gender' => 'female'],
            ['name' => 'Ivan Maulana',        'email' => 'ivan.maulana@maznusantara.co.id',     'division' => 'Teknologi Informasi', 'job' => 'IT Engineer',      'gender' => 'male'],
        ];

        $cities = ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi'];
        $eduList = ['S1', 'S2', 'D3', 'SMA'];

        foreach ($karyawanList as $i => $data) {
            User::factory()->create([
                'nip' => '2024' . str_pad($i + 10, 6, '0', STR_PAD_LEFT),
                'name' => $data['name'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'phone' => '0812' . fake()->numerify('########'),
                'division_id' => $divisions[$data['division']] ?? null,
                'job_title_id' => $jobTitles[$data['job']] ?? null,
                'education_id' => $educations[fake()->randomElement($eduList)] ?? null,
                'city' => fake()->randomElement($cities),
                'address' => fake()->streetAddress(),
                'birth_place' => fake()->randomElement($cities),
                'birth_date' => fake()->dateTimeBetween('-45 years', '-22 years')->format('Y-m-d'),
                'role' => User::ROLE_KARYAWAN,
                'status' => User::STATUS_ACTIVE,
                'password' => Hash::make('password'),
                'raw_password' => 'password',
            ]);
        }
    }
}
