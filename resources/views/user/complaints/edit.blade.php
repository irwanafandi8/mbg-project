<x-layouts.user :title="'Edit Aduan #' . $complaint->complaint_number">

    <div>
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('user.complaints.show', $complaint) }}" class="btn-outline btn-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <span class="font-mono text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">
                {{ $complaint->complaint_number }}
            </span>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Edit Aduan</h3>
                <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Hanya aduan berstatus "Menunggu"
                    yang dapat diedit</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.complaints.update', $complaint) }}"
                    enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id"
                                class="form-select mt-1 {{ $errors->has('category_id') ? 'error' : '' }}">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $complaint->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Prioritas <span class="text-red-500">*</span></label>
                            <select name="priority" class="form-select mt-1">
                                @foreach (\App\Enums\ComplaintPriority::cases() as $p)
                                    <option value="{{ $p->value }}"
                                        {{ old('priority', $complaint->priority->value) === $p->value ? 'selected' : '' }}>
                                        {{ $p->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $complaint->title) }}"
                            placeholder="Ringkasan singkat tentang keluhan Anda..."
                            class="form-input mt-1 {{ $errors->has('title') ? 'error' : '' }}">
                        @error('title')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="form-hint">Minimal 10 karakter, maksimal 255 karakter</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5"
                            placeholder="Jelaskan keluhan Anda secara detail: apa yang terjadi, kapan, dan dampaknya..."
                            class="form-textarea mt-1 {{ $errors->has('description') ? 'error' : '' }}">{{ old('description', $complaint->description) }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="form-hint">Minimal 20 karakter</p>
                    </div>

                    {{-- Existing Attachments --}}
                    @if ($complaint->attachments->count())
                        <div class="mb-4">
                            <label class="form-label">Lampiran Saat Ini</label>
                            <div class="grid grid-cols-3 gap-2 mt-1">
                                @foreach ($complaint->attachments as $att)
                                    @if ($att->is_image)
                                        <img src="{{ Storage::url($att->file_path) }}" alt="{{ $att->file_name }}"
                                            class="w-full h-20 object-cover rounded-lg border border-slate-200 mt-1">
                                    @else
                                        <div
                                            class="h-20 bg-slate-50 rounded-lg border border-slate-200 flex flex-col items-center justify-center">
                                            <svg class="w-5 h-5 text-slate-400 mb-1" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-xs text-slate-500 truncate px-1">{{ $att->file_name }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="form-label">Tambah Lampiran Baru</label>
                        <label class="file-upload-zone mt-1" for="attachments_edit">
                            <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-slate-500">Klik untuk menambah file baru</p>
                            <p class="form-hint">Anda dapat memilih lebih dari 1 file (maks. 5 file)</p>
                        </label>
                        <input type="file" id="attachments_edit" name="attachments[]" multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf" class="hidden">
                        @error('attachments')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        @error('attachments.*')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary flex-1 justify-center py-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('user.complaints.show', $complaint) }}" class="btn-outline px-6">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.user>
