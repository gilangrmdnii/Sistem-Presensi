<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $karyawan = User::where('role', User::ROLE_KARYAWAN)->get();
        $atasans = User::whereIn('role', [User::ROLE_ATASAN_DIVISI, User::ROLE_HRD])->get();
        if ($karyawan->isEmpty() || $atasans->isEmpty()) {
            return;
        }

        $reasons = [
            'izin' => [
                'Menghadiri acara pernikahan saudara kandung',
                'Mengurus dokumen keluarga di kantor pencatatan sipil',
                'Mengantar orang tua ke rumah sakit untuk pemeriksaan rutin',
                'Keperluan keluarga yang tidak dapat ditinggalkan',
                'Menjadi wali murid dalam acara kelulusan anak',
            ],
            'cuti' => [
                'Cuti tahunan untuk liburan bersama keluarga',
                'Cuti menikah',
                'Cuti melahirkan',
                'Cuti tahunan — istirahat dan refresh',
            ],
            'sakit' => [
                'Demam tinggi dan flu, istirahat sesuai anjuran dokter',
                'Sakit perut hebat, perlu istirahat dan pengobatan',
                'Sakit kepala migrain berat',
                'Infeksi saluran pernapasan, disarankan bed rest',
            ],
        ];

        // 1. Pending (menunggu) — 6 pengajuan terbaru
        for ($i = 0; $i < 6; $i++) {
            $user = $karyawan->random();
            $type = fake()->randomElement(['izin', 'cuti', 'sakit']);
            $start = Carbon::now()->addDays(mt_rand(1, 14));
            LeaveRequest::create([
                'user_id' => $user->id,
                'leave_type' => $type,
                'start_date' => $start->toDateString(),
                'end_date' => $start->copy()->addDays(mt_rand(0, 3))->toDateString(),
                'reason' => fake()->randomElement($reasons[$type]),
                'status' => LeaveRequest::STATUS_PENDING,
                'created_at' => Carbon::now()->subDays(mt_rand(0, 3)),
                'updated_at' => Carbon::now()->subDays(mt_rand(0, 3)),
            ]);
        }

        // 2. Approved — 10 pengajuan
        for ($i = 0; $i < 10; $i++) {
            $user = $karyawan->random();
            $approver = $atasans->random();
            $type = fake()->randomElement(['izin', 'cuti', 'sakit']);
            $createdAt = Carbon::now()->subDays(mt_rand(10, 45));
            $start = $createdAt->copy()->addDays(mt_rand(2, 10));
            LeaveRequest::create([
                'user_id' => $user->id,
                'leave_type' => $type,
                'start_date' => $start->toDateString(),
                'end_date' => $start->copy()->addDays(mt_rand(0, 4))->toDateString(),
                'reason' => fake()->randomElement($reasons[$type]),
                'status' => LeaveRequest::STATUS_APPROVED,
                'approved_by' => $approver->id,
                'approved_at' => $createdAt->copy()->addHours(mt_rand(2, 24)),
                'approver_note' => fake()->randomElement([
                    'Disetujui, semoga cepat pulih.',
                    'OK, pastikan pending task di-handover ke rekan tim.',
                    'Disetujui. Jangan lupa update Jira sebelum cuti.',
                    'Disetujui.',
                ]),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addHours(mt_rand(2, 24)),
            ]);
        }

        // 3. Rejected — 4 pengajuan
        for ($i = 0; $i < 4; $i++) {
            $user = $karyawan->random();
            $approver = $atasans->random();
            $type = fake()->randomElement(['izin', 'cuti']);
            $createdAt = Carbon::now()->subDays(mt_rand(5, 30));
            $start = $createdAt->copy()->addDays(mt_rand(2, 10));
            LeaveRequest::create([
                'user_id' => $user->id,
                'leave_type' => $type,
                'start_date' => $start->toDateString(),
                'end_date' => $start->copy()->addDays(mt_rand(0, 3))->toDateString(),
                'reason' => fake()->randomElement($reasons[$type]),
                'status' => LeaveRequest::STATUS_REJECTED,
                'approved_by' => $approver->id,
                'approved_at' => $createdAt->copy()->addHours(mt_rand(3, 48)),
                'approver_note' => fake()->randomElement([
                    'Ditolak, minggu tersebut ada deadline proyek. Ajukan ulang minggu depan.',
                    'Jadwal bertabrakan dengan cuti anggota tim lain, mohon reschedule.',
                    'Tolong ajukan minimal 3 hari kerja sebelumnya.',
                    'Dokumen pendukung belum dilampirkan, silakan ajukan ulang dengan lampiran.',
                ]),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addHours(mt_rand(3, 48)),
            ]);
        }
    }
}
