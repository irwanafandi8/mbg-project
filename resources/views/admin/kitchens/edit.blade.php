<x-layouts.admin :title="$kitchen->name">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ admin_route('kitchens.index') }}" class="btn-outline btn-sm">Kembali</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Edit Dapur</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ admin_route('kitchens.update', $kitchen) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Dapur <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $kitchen->name) }}"
                                placeholder="Contoh: Dapur MBG Kecamatan A" class="form-input mt-1 {{ $errors->has('name') ? 'error' : '' }}" required>
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Penanggung Jawab <span class="text-red-500">*</span></label>
                            <input type="text" name="person_in_charge"
                                value="{{ old('person_in_charge', $kitchen->person_in_charge) }}"
                                class="form-input mt-1 {{ $errors->has('person_in_charge') ? 'error' : '' }}" placeholder="Nama penanggung jawab dapur" required>
                            @error('person_in_charge')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" class="form-textarea mt-1 {{ $errors->has('address') ? 'error' : '' }}" placeholder="Masukkan alamat lengkap dapur" required>{{ old('address', $kitchen->address) }}</textarea>
                            @error('address')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $kitchen->phone) }}"
                                placeholder="Contoh: 081234567890" class="form-input mt-1 {{ $errors->has('phone') ? 'error' : '' }}">
                            @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Kapasitas Produksi <span class="text-red-500">*</span></label>
                            <input type="number" name="production_capacity" min="1"
                                value="{{ old('production_capacity', $kitchen->production_capacity) }}"
                                placeholder="Contoh: 500" class="form-input mt-1 {{ $errors->has('production_capacity') ? 'error' : '' }}" required>
                            @error('production_capacity')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Status Operasional</label>
                        <select name="operational_status" class="form-select mt-1 {{ $errors->has('operational_status') ? 'error' : '' }}">
                            @foreach (\App\Enums\OperationalStatus::cases() as $status)
                                <option value="{{ $status->value }}"
                                    {{ old('operational_status', $kitchen->operational_status->value) === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('operational_status')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ admin_route('kitchens.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
