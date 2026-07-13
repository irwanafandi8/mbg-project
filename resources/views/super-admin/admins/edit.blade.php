<x-layouts.admin :title="'Edit: ' . $user->name">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('super_admin.admins.index') }}" class="btn-outline btn-sm">Kembali</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Edit Admin Sekolah</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('super_admin.admins.update', $user) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                placeholder="Masukkan nama lengkap" class="form-input mt-1" required>
                        </div>
                        <div>
                            <label class="form-label">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                placeholder="contoh@email.com" class="form-input mt-1" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Sekolah <span class="text-red-500">*</span></label>
                            <select name="school_id" class="form-select mt-1" required>
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($schools as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('school_id', $user->school_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="Contoh: 081234567890" class="form-input mt-1">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Password Baru (opsional)</label>
                            <input type="password" name="password" class="form-input mt-1"
                                placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div>
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-input mt-1"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            class="w-4 h-4 text-blue-600 border-slate-300 rounded"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-slate-700">Akun aktif</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ route('super_admin.admins.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
