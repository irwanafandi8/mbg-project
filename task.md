# Task Revisi Sistem - SPPG Indramayu Karanganyar 2

## 1. Hapus Fitur Prioritas dari Aduan

### Deskripsi
Semua aduan bersifat prioritas, sehingga field prioritas (low/medium/high) dihapus dari sistem.

### File yang Diubah

| No | File | Perubahan |
|----|------|-----------|
| 1 | `app/Enums/ComplaintPriority.php` | Hapus file ini seluruhnya |
| 2 | `database/migrations/xxxx_create_drop_priority_column.php` | Migration baru: drop kolom `priority` dari tabel `complaints` |
| 3 | `app/Models/Complaint.php:27` | Hapus `'priority'` dari array `$fillable` |
| 4 | `app/Models/Complaint.php:38` | Hapus `'priority' => ComplaintPriority::class` dari method `casts()` |
| 5 | `app/Http/Requests/Admin/StoreComplaintRequest.php:20` | Hapus rule `'priority' => ['required', 'in:low,medium,high']` |
| 6 | `app/Http/Requests/User/StoreComplaintRequest.php:20` | Hapus rule `'priority' => ['required', 'in:low,medium,high']` |
| 7 | `app/Http/Requests/User/UpdateComplaintRequest.php:24` | Hapus rule `'priority' => ['required', 'in:low,medium,high']` |
| 8 | `resources/views/admin/complaints/index.blade.php:104` | Hapus kolom header `<th>Prioritas</th>` |
| 9 | `resources/views/admin/complaints/index.blade.php:124-128` | Hapus cell priority badge |
| 10 | `resources/views/admin/complaints/index.blade.php:149` | Update colspan dari 9 ke 8 |
| 11 | `resources/views/admin/complaints/create.blade.php:50-64` | Hapus div field "Prioritas" (label + select) |
| 12 | `resources/views/admin/complaints/show.blade.php:20-22` | Hapus badge prioritas di header detail |
| 13 | `resources/views/user/complaints/index.blade.php:35` | Hapus kolom header `<th>Prioritas</th>` |
| 14 | `resources/views/user/complaints/index.blade.php:52-55` | Hapus cell priority badge |
| 15 | `resources/views/user/complaints/index.blade.php:101` | Update colspan dari 9 ke 8 |
| 16 | `resources/views/user/complaints/create.blade.php:51-65` | Hapus div field "Prioritas" (label + select) |
| 17 | `resources/views/user/complaints/show.blade.php:68-72` | Hapus div "Prioritas" dari detail grid |
| 18 | `resources/views/user/complaints/edit.blade.php:44-57` | Hapus div field "Prioritas" (label + select) |

### Acceptance Criteria
- [ ] Enum `ComplaintPriority` dihapus
- [ ] Kolom `priority` di-drop dari database
- [ ] Form buat aduan (user & admin) tidak menampilkan field prioritas
- [ ] Daftar aduan (user & admin) tidak menampilkan kolom prioritas
- [ ] Detail aduan (user & admin) tidak menampilkan badge prioritas
- [ ] Semua form request tidak memvalidasi priority
- [ ] Tidak ada error pada seluruh halaman terkait aduan

---

## 2. Rename "SPPG MBG" → "SPPG Indramayu Karanganyar 2"

### Deskripsi
Ganti seluruh label/branding "SPPG MBG" menjadi "SPPG Indramayu Karanganyar 2".

### File yang Diubah

| No | File | Baris | Perubahan |
|----|------|-------|-----------|
| 1 | `resources/views/components/layouts/admin.blade.php` | 9 | `SPPG MBG` → `SPPG Indramayu Karanganyar 2` |
| 2 | `resources/views/components/layouts/admin.blade.php` | 308 | Footer: `SPPG MBG` → `SPPG Indramayu Karanganyar 2` |
| 3 | `resources/views/components/layouts/user.blade.php` | 9 | `SPPG MBG` → `SPPG Indramayu Karanganyar 2` |
| 4 | `resources/views/components/layouts/user.blade.php` | 144 | Footer: `SPPG MBG` → `SPPG Indramayu Karanganyar 2` |

### Acceptance Criteria
- [ ] Sidebar admin menampilkan "SPPG Indramayu Karanganyar 2"
- [ ] Sidebar user menampilkan "SPPG Indramayu Karanganyar 2"
- [ ] Footer kedua layout menampilkan nama baru
- [ ] Tidak ada lagi teks "SPPG MBG" di seluruh view

---

## 3. Fix: Aduan User Tidak Muncul di Daftar Aduan

### Deskripsi
Ketika user baru membuat aduan, aduannya tidak muncul di daftar aduan milik user tersebut (padahal di admin/super admin aduan terlihat).

### Analisis Masalah
- Query di `User\ComplaintController::index()` menggunakan `auth()->user()->complaints()` — benar
- Kemungkinan besar masalah di **relasi data**: user yang dibuat admin mungkin tidak punya `school_id` yang valid, atau school-nya tidak terhubung ke kitchen (`kitchen_id` null)
- Jika `school->kitchen_id` null, user akan kena `abort_if` di `User\ComplaintController::create()` (baris 47) dan tidak bisa membuat aduan sama sekali
- Kemungkinan lain: aduan tersimpan dengan `user_id` yang benar, tapi view tidak menampilkannya karena ada error pada eager loading relasi

### File yang Diperiksa/Diperbaiki

| No | File | Perubahan |
|----|------|-----------|
| 1 | `app/Http/Controllers/User/ComplaintController.php:25-27` | Pastikan query eager loading `user.school` atau setidaknya `kitchen` tidak nullable |
| 2 | `app/Http/Controllers/Admin/UserController.php:84-115` | Pastikan saat admin membuat user, `school_id` terisi dengan benar |
| 3 | `resources/views/admin/users/create.blade.php:14` | Hidden input `role=user` sudah ada — pastikan `school_id` juga terkirim |
| 4 | `app/Http/Controllers/User/ComplaintController.php:47` | Pertimbangkan untuk mengganti `abort_if` dengan flash message yang lebih informatif |

### Action Items
1. **Debug logging**: Tambahkan log di `User\ComplaintController::index()` untuk memastikan query mengembalikan data
2. **Validasi relasi**: Pastikan setiap user memiliki `school_id` dan school memiliki `kitchen_id`
3. **Cek migration**: Pastikan `kitchen_id` di tabel `schools` sudah nullable atau memiliki default
4. **Tambah fallback**: Jika user tidak punya school/kitchen, tampilkan pesan yang jelas di halaman buat aduan

### Acceptance Criteria
- [ ] User yang baru dibuat admin bisa login dan melihat daftar aduan kosong (bukan error)
- [ ] Setelah user membuat aduan, aduan muncul di daftar aduan user
- [ ] Aduan yang sama juga muncul di daftar aduan admin dan super admin
- [ ] Jika user belum punya school/kitchen, dapat pesan yang informatif

---

## 4. Hapus Aksi Edit dan Hapus di Daftar Aduan (Admin & Super Admin)

### Deskripsi
Admin dan super admin tidak boleh bisa mengedit atau menghapus aduan user (tidak etis).

### Status Saat Ini
Berdasarkan analisis kode:
- `admin/complaints/index.blade.php` — **Sudah benar**, hanya ada tombol "Lihat Detail" (eye icon)
- `admin/complaints/show.blade.php` — Super admin bisa **update status** (bukan edit isi aduan), admin sekolah hanya melihat status
- `routes/web.php:53-56` — Super admin hanya punya route `index`, `show`, `updateStatus` — tidak ada `edit`, `update`, `destroy`

### File yang Diverifikasi

| No | File | Status |
|----|------|--------|
| 1 | `resources/views/admin/complaints/index.blade.php:134-144` | OK — hanya tombol "Lihat" |
| 2 | `resources/views/admin/complaints/show.blade.php:183-221` | OK — hanya update status untuk super admin |
| 3 | `routes/web.php:53-56` | OK — tidak ada route edit/delete untuk complaints |
| 4 | `app/Http/Controllers/Admin/ComplaintController.php` | OK — tidak ada method edit/update/destroy |

### Acceptance Criteria
- [ ] Daftar aduan admin/super admin hanya menampilkan tombol "Lihat Detail"
- [ ] Tidak ada route untuk edit/delete aduan di admin/super admin
- [ ] Super admin masih bisa mengubah status aduan (fitur ini diperlukan)
- [ ] Admin sekolah hanya bisa melihat status, tidak bisa mengubah

---

## 5. Hapus Menu & Daftar SPPG/Dapur

### Deskripsi
Karena mitra/client hanya fokus ke SPPG Indramayu Karanganyar 2, menu dan daftar SPPG/Dapur dihapus dari UI.

### File yang Diubah

| No | File | Perubahan |
|----|------|-----------|
| 1 | `routes/web.php:44-51` | Hapus seluruh block route `super_admin.kitchens.*` |
| 2 | `resources/views/components/layouts/admin.blade.php:101-109` | Hapus link sidebar "SPPG / Dapur" |
| 3 | `resources/views/super-admin/dashboard.blade.php:103-115` | Hapus stat card "Total SPPG / Dapur" |
| 4 | `app/Http/Controllers/SuperAdmin/DashboardController.php:31` | Hapus `$totalKitchens = Kitchen::count()` |
| 5 | `app/Http/Controllers/SuperAdmin/DashboardController.php:57` | Hapus `'totalKitchens'` dari compact() |
| 6 | `app/Http/Controllers/Admin/DashboardController.php:30` | Hapus `$totalKitchens = Kitchen::count()` |
| 7 | `app/Http/Controllers/Admin/DashboardController.php:54` | Hapus `'totalKitchens'` dari compact() |
| 8 | `resources/views/admin/dashboard.blade.php` | Cek apakah ada stat card kitchens (admin dashboard tidak punya — sudah OK) |

### Catatan Penting
- **JANGAN hapus** `app/Models/Kitchen.php` — masih diperlukan untuk relasi `School` dan `Complaint`
- **JANGAN hapus** migration `create_kitchens_table` — database tetap membutuhkan tabel ini
- **JANGAN hapus** `Admin/KitchenController.php` — cukup hapus routes-nya dari web.php
- `app/Enums/OperationalStatus.php` — masih diperlukan oleh Kitchen model

### Acceptance Criteria
- [ ] Sidebar admin/super admin tidak menampilkan menu "SPPG / Dapur"
- [ ] Route `super_admin.kitchens.*` tidak bisa diakses (404)
- [ ] Dashboard super admin tidak menampilkan stat card kitchens
- [ ] Dashboard admin tidak terpengaruh (sudah tidak ada stat kitchens)
- [ ] Model, migration, dan enum tetap ada (untuk relasi database)

---

## 6. Fitur Bulk Buat Akun User (Siswa/i & Wali Murid)

### Deskripsi
Admin sekolah perlu membuat banyak akun user sekaligus karena pembuatan satu-satu terlalu lambat.

### Solusi: Form Multi-baris Dinamis
Form dengan dynamic rows (JavaScript) yang bisa menambah beberapa user sekaligus dalam satu halaman.

### Interface

```
+----------------------------------------------------------+
| Tambah Pengguna Massal                                    |
+----------------------------------------------------------+
| No | Nama Lengkap | Email | No. Telepon | Password        |
|----|-------------|-------|-------------|------------------|
| 1  | [________] | [___] | [_________] | [________]       |
| 2  | [________] | [___] | [_________] | [________]       |
| 3  | [________] | [___] | [_________] | [________]       |
| [+ Tambah Baris]                                         |
+----------------------------------------------------------+
| Password Default: [________________] (jika kosong auto)   |
| [Simpan Semua]  [Batal]                                  |
+----------------------------------------------------------+
```

### File yang Dibuat/Diubah

| No | File | Perubahan |
|----|------|-----------|
| 1 | `routes/web.php` | Tambah route `GET /users/bulk-create` (admin & super admin) |
| 2 | `routes/web.php` | Tambah route `POST /users/bulk` (admin & super admin) |
| 3 | `app/Http/Controllers/Admin/UserController.php` | Tambah method `bulkCreate()` — show form |
| 4 | `app/Http/Controllers/Admin/UserController.php` | Tambah method `bulkStore()` — process data |
| 5 | `resources/views/admin/users/bulk-create.blade.php` | **FILE BARU** — form bulk creation |
| 6 | `resources/views/admin/users/index.blade.php:47` | Tambah tombol "Import User" di samping "Tambah Pengguna" |

### Backend Logic (`bulkStore`)

```php
public function bulkStore(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'users' => ['required', 'array', 'min:1'],
        'users.*.name' => ['required', 'string', 'max:255'],
        'users.*.email' => ['required', 'email', 'unique:users,email'],
        'users.*.phone' => ['nullable', 'string', 'max:20'],
        'default_password' => ['nullable', 'string', 'min:8'],
    ]);

    $password = $validated['default_password'] ?? 'password123';
    $success = 0;
    $errors = [];

    DB::beginTransaction();
    try {
        foreach ($validated['users'] as $index => $userData) {
            $userData['password'] = Hash::make($password);
            $userData['role'] = UserRole::USER;
            $userData['school_id'] = auth()->user()->school_id;
            $userData['is_active'] = true;

            $user = User::create($userData);
            AuditLog::log('create_user', User::class, $user->id, null, [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->value,
            ]);
            $success++;
        }
        DB::commit();

        return redirect()->route(admin_route_name() . '.users.index')
            ->with('success', "{$success} pengguna berhasil ditambahkan.");
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
    }
}
```

### Frontend (JavaScript)

```javascript
// Dynamic rows: tambah/hapus baris form
// Auto-generate email dari nama (opsional)
// Validasi client-side sebelum submit
// Show loading saat submit
```

### Acceptance Criteria
- [ ] Admin sekolah bisa mengakses halaman "Tambah Pengguna Massal"
- [ ] Form memiliki tombol "+ Tambah Baris" untuk menambah field
- [ ] Setiap baris minimal ada: Nama, Email (required), Telepon (opsional)
- [ ] Password default bisa diatur (default: `password123`)
- [ ] Submit semua user sekaligus, dengan validasi per baris
- [ ] Jika ada error, tampilkan ringkasan (X berhasil, Y gagal dengan detail)
- [ ] Setiap user yang berhasil dibuat di-log di audit_logs
- [ ] Super admin juga bisa menggunakan fitur ini (dengan pilihan sekolah)
- [ ] Tombol "Import User" tersedia di halaman daftar pengguna

---

## Urutan Pengerjaan (Rekomendasi)

| Prioritas | Task | Estimasi |
|-----------|------|----------|
| 1 | Task 2: Rename SPPG MBG | Mudah (4 file, cari-ganti) |
| 2 | Task 5: Hapus Menu SPPG/Dapur | Mudah (hapus route, sidebar, stat card) |
| 3 | Task 1: Hapus Fitur Prioritas | Sedang (18 file, termasuk migration) |
| 4 | Task 4: Verifikasi Hapus Edit/Hapus Aduan | Mudah (verifikasi saja) |
| 5 | Task 3: Fix Aduan User | Sedang (perlu debug data) |
| 6 | Task 6: Bulk Buat Akun User | Berat (feature baru, form + controller + JS) |

---

## Catatan Teknis

- **Framework**: Laravel (PHP)
- **Database**: MySQL (enum type)
- **Frontend**: Blade templates + Tailwind CSS + Vanilla JS
- **Authentication**: Laravel Auth + custom RoleMiddleware
- **Role**: `super_admin`, `admin`, `user` (enum di database)
- **Helper**: `admin_route()` dan `admin_route_name()` di `app/Helpers/helpers.php`
