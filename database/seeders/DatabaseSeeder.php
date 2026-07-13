<?php

namespace Database\Seeders;

use App\Enums\OperationalStatus;
use App\Enums\UserRole;
use App\Models\ComplaintCategory;
use App\Models\Kitchen;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        User::create([
            'name' => 'Super Admin MBG',
            'email' => 'superadmin@mbg.go.id',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPER_ADMIN,
            'is_active' => true,
        ]);

        // 2. Create Categories
        $categories = [
            [
                'name' => 'Kualitas Makanan',
                'description' => 'Terkait rasa, tingkat kematangan, atau tekstur makanan.',
            ],
            [
                'name' => 'Kebersihan & Higienitas',
                'description' => 'Terkait kebersihan kemasan, adanya benda asing, atau kebersihan alat makan.',
            ],
            [
                'name' => 'Ketepatan Waktu',
                'description' => 'Terkait keterlambatan pengiriman makanan ke sekolah.',
            ],
            [
                'name' => 'Porsi & Kelengkapan Menu',
                'description' => 'Terkait porsi yang kurang atau menu yang tidak lengkap/sesuai jadwal.',
            ],
            [
                'name' => 'Kerusakan Kemasan',
                'description' => 'Terkait kotak makan atau plastik penutup yang rusak/terbuka.',
            ],
            [
                'name' => 'Pelayanan Petugas',
                'description' => 'Terkait sikap petugas pengiriman atau personil dapur.',
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Kategori pengaduan di luar daftar di atas.',
            ],
        ];

        foreach ($categories as $cat) {
            ComplaintCategory::create($cat);
        }

        // 3. Create Kitchens (Dapur MBG)
        $kitchens = [
            [
                'name' => 'Dapur MBG Pusat - Kebayoran',
                'address' => 'Jl. Kebayoran Lama No. 123, Jakarta Selatan',
                'phone' => '021-7221234',
                'person_in_charge' => 'Bpk. Ahmad Sujarwo',
                'production_capacity' => 2000,
                'operational_status' => OperationalStatus::ACTIVE,
            ],
            [
                'name' => 'Dapur MBG Wilayah 2 - Tebet',
                'address' => 'Jl. Dr. Saharjo No. 45, Tebet, Jakarta Selatan',
                'phone' => '021-8314567',
                'person_in_charge' => 'Ibu Maria Ulfa',
                'production_capacity' => 1500,
                'operational_status' => OperationalStatus::ACTIVE,
            ],
            [
                'name' => 'Dapur MBG Wilayah 3 - Jagakarsa',
                'address' => 'Jl. Sirsak No. 88, Jagakarsa, Jakarta Selatan',
                'phone' => '021-7890123',
                'person_in_charge' => 'Bpk. Hendra Wijaya',
                'production_capacity' => 1200,
                'operational_status' => OperationalStatus::ACTIVE,
            ],
        ];

        foreach ($kitchens as $kData) {
            Kitchen::create($kData);
        }

        // 4. Create Schools
        $schools = [
            [
                'name' => 'SDN 01 Kebayoran Lama',
                'npsn' => '20101231',
                'address' => 'Jl. Kebayoran No. 1, Jakarta Selatan',
                'kitchen_id' => 1,
                'admin_email' => 'admin.sdn01@sch.id',
                'admin_name' => 'Pak Budi Santoso',
            ],
            [
                'name' => 'SMPN 45 Jakarta',
                'npsn' => '20104567',
                'address' => 'Jl. Tebet Timur No. 10, Jakarta Selatan',
                'kitchen_id' => 2,
                'admin_email' => 'admin.smpn45@sch.id',
                'admin_name' => 'Ibu Ratna Dewi',
            ],
            [
                'name' => 'SMAN 38 Jakarta',
                'npsn' => '20108910',
                'address' => 'Jl. Lenteng Agung No. 5, Jakarta Selatan',
                'kitchen_id' => 3,
                'admin_email' => 'admin.sman38@sch.id',
                'admin_name' => 'Pak Drs. Hadi Susilo',
            ],
        ];

        foreach ($schools as $sData) {
            $adminEmail = $sData['admin_email'];
            $adminName = $sData['admin_name'];
            unset($sData['admin_email'], $sData['admin_name']);

            $school = School::create($sData);

            // Create Admin Sekolah
            User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'school_id' => $school->id,
                'is_active' => true,
            ]);

            // Create User (Siswa/Orang Tua) for each school
            User::create([
                'name' => 'Andi Saputra (Wali Murid)',
                'email' => "andi.{$school->npsn}@gmail.com",
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'school_id' => $school->id,
                'phone' => '081234567890',
                'is_active' => true,
            ]);

            User::create([
                'name' => 'Siti Rahmawati (Siswa)',
                'email' => "siti.{$school->npsn}@gmail.com",
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'school_id' => $school->id,
                'phone' => '081298765432',
                'is_active' => true,
            ]);
        }

        // Create one unmapped school for testing UI markers
        School::create([
            'name' => 'Sekolah Percobaan (Belum Mapping)',
            'npsn' => '99999999',
            'address' => 'Jl. Contoh Saja No. 0',
            'is_active' => true,
        ]);
    }
}
