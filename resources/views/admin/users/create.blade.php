<x-layouts.admin title="Tambah Pengguna">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ admin_route('users.index') }}" class="btn-outline btn-sm">Kembali</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Tambah Pengguna (Siswa/Orang Tua)</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ admin_route('users.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" value="user">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1"
                                placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div>
                            <label class="form-label">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-input mt-1"
                                placeholder="contoh@email.com" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Sekolah <span class="text-red-500">*</span></label>
                            <select name="school_id" class="form-select mt-1" required>
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($schools as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('school_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="Contoh: 081234567890" class="form-input mt-1">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" class="form-input mt-1"
                                placeholder="Masukkan password" required>
                        </div>
                        <div>
                            <label class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" class="form-input mt-1"
                                placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ admin_route('users.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary">Simpan Pengguna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
