<x-layouts.admin :title="$user->name">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.users.index') }}" class="btn-outline btn-sm">Kembali</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Edit Pengguna</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
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
                            <label class="form-label">Peran</label>
                            <select name="role" id="role" class="form-select mt-1"
                                onchange="toggleSchoolField()">
                                <option value="user"
                                    {{ old('role', $user->role->value) === 'user' ? 'selected' : '' }}>Administrator
                                    Sekolah</option>
                                <option value="admin"
                                    {{ old('role', $user->role->value) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div id="schoolField">
                            <label class="form-label">Sekolah</label>
                            <select name="school_id" class="form-select mt-1">
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($schools as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('school_id', $user->school_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="Contoh: 081234567890" class="form-input mt-1">
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
                        <a href="{{ route('admin.users.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleSchoolField() {
                const role = document.getElementById('role').value;
                const schoolField = document.getElementById('schoolField');
                schoolField.style.display = role === 'admin' ? 'none' : 'block';
            }

            toggleSchoolField();
        </script>
    @endpush
</x-layouts.admin>
