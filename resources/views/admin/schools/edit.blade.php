<x-layouts.admin :title="$school->name">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.schools.index') }}" class="btn-outline btn-sm">Kembali</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Edit Sekolah</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.schools.update', $school) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $school->name) }}"
                                placeholder="Contoh: SD Negeri 01" class="form-input mt-1" required>
                        </div>
                        <div>
                            <label class="form-label">NPSN <span class="text-red-500">*</span></label>
                            <input type="text" name="npsn" value="{{ old('npsn', $school->npsn) }}"
                                placeholder="Masukkan NPSN sekolah" class="form-input mt-1" required>
                        </div>
                        <div>
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $school->phone) }}"
                                placeholder="Contoh: 0211234567" class="form-input mt-1">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" class="form-textarea mt-1" placeholder="Masukkan alamat lengkap sekolah"
                            required>{{ old('address', $school->address) }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Pemetaan Dapur MBG</label>
                        <select name="kitchen_id" class="form-select mt-1">
                            <option value="">-- Tanpa Dapur --</option>
                            @foreach ($kitchens as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('kitchen_id', $school->kitchen_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            class="w-4 h-4 text-blue-600 border-slate-300 rounded"
                            {{ old('is_active', $school->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-slate-700">Sekolah aktif</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.schools.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
