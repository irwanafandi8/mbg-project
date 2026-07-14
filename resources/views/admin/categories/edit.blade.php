<x-layouts.admin :title="'Edit Kategori: ' . $category->name" breadcrumb="Perbarui kategori aduan">

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ admin_route('categories.index') }}" class="btn-outline btn-sm">Kembali</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Edit Kategori</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ admin_route('categories.update', $category) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="form-label">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            placeholder="cth: Kualitas Makanan"
                            class="form-input mt-1 {{ $errors->has('name') ? 'error' : '' }}">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" rows="4" placeholder="Deskripsi kategori..." class="form-textarea mt-1">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            class="w-4 h-4 text-blue-600 border-slate-300 rounded"
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-slate-700">Aktif</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ admin_route('categories.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.admin>
