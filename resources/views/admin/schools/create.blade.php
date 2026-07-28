<x-layouts.admin title="Tambah Sekolah">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ admin_route('schools.index') }}" class="btn-outline btn-sm">Kembali</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Tambah Sekolah</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ admin_route('schools.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1 {{ $errors->has('name') ? 'error' : '' }}"
                                placeholder="Contoh: SD Negeri 01" required>
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">NPSN <span class="text-red-500">*</span></label>
                            <input type="text" name="npsn" value="{{ old('npsn') }}" class="form-input mt-1 {{ $errors->has('npsn') ? 'error' : '' }}"
                                placeholder="Masukkan NPSN sekolah" required>
                            @error('npsn')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="Contoh: 0211234567" class="form-input mt-1 {{ $errors->has('phone') ? 'error' : '' }}">
                            @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" class="form-textarea mt-1 {{ $errors->has('address') ? 'error' : '' }}" placeholder="Masukkan alamat lengkap sekolah"
                            required>{{ old('address') }}</textarea>
                        @error('address')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Pemetaan Dapur MBG</label>
                        <select name="kitchen_id" class="form-select mt-1 {{ $errors->has('kitchen_id') ? 'error' : '' }}">
                            <option value="">-- Pilih Dapur --</option>
                            @foreach ($kitchens as $k)
                                <option value="{{ $k->id }}" {{ old('kitchen_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }}</option>
                            @endforeach
                        </select>
                        @error('kitchen_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ admin_route('schools.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary">Simpan Sekolah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
