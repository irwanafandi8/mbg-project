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

        // 3. Create Kitchen (SPPG Indramayu Karanganyar 2)
        $kitchen = Kitchen::create([
            'name' => 'SPPG Indramayu Karanganyar 2',
            'address' => 'Jl. Raya Karanganyar No. 1, Indramayu, Jawa Barat',
            'phone' => '0234-123456',
            'person_in_charge' => 'Bpk. Ahmad Sujarwo',
            'production_capacity' => 2000,
            'operational_status' => OperationalStatus::ACTIVE,
        ]);

        // 4. Create Schools
        $schools = [
            [
                'name' => 'SDN 1 Karanganyar',
                'npsn' => '20200001',
                'address' => 'Jl. Karanganyar No. 10, Indramayu',
                'kitchen_id' => $kitchen->id,
                'admin_email' => 'admin.sdn1@sch.id',
                'admin_name' => 'Pak Budi Santoso',
            ],
            [
                'name' => 'SMPN 1 Karanganyar',
                'npsn' => '20200002',
                'address' => 'Jl. Karanganyar No. 25, Indramayu',
                'kitchen_id' => $kitchen->id,
                'admin_email' => 'admin.smpn1@sch.id',
                'admin_name' => 'Ibu Ratna Dewi',
            ],
            [
                'name' => 'SMAN 1 Karanganyar',
                'npsn' => '20200003',
                'address' => 'Jl. Karanganyar No. 50, Indramayu',
                'kitchen_id' => $kitchen->id,
                'admin_email' => 'admin.sman1@sch.id',
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
    }
}
