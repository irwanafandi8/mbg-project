<x-layouts.admin title="Buat Aduan Baru" breadcrumb="Buat aduan untuk sekolah Anda">

    <div>
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ admin_route('complaints.index') }}" class="btn-outline btn-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="alert-info mb-6">
            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p>Aduan akan dikirim ke: <span
                        class="font-semibold">{{ $school->kitchen?->name }}</span></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Formulir Pengaduan</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ admin_route('complaints.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Kategori Aduan <span class="text-red-500">*</span></label>
                        <select name="category_id"
                            class="form-select {{ $errors->has('category_id') ? 'error' : '' }}">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Judul Aduan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="Ringkasan singkat tentang keluhan Anda..."
                            class="form-input {{ $errors->has('title') ? 'error' : '' }}">
                        @error('title')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="form-hint">Minimal 10 karakter, maksimal 255 karakter</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi Aduan <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5"
                            placeholder="Jelaskan keluhan Anda secara detail: apa yang terjadi, kapan, dan dampaknya..."
                            class="form-textarea {{ $errors->has('description') ? 'error' : '' }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="form-hint">Minimal 20 karakter</p>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Lampiran Bukti (Opsional)</label>
                        <label class="file-upload-zone" for="attachments">
                            <svg class="w-10 h-10 text-slate-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-slate-600 mb-1">Klik atau seret file ke sini</p>
                            <p class="form-hint">JPEG, PNG, WebP, PDF - Max. 5 MB per file (maks. 5 file)</p>
                        </label>
                        <input type="file" id="attachments" name="attachments[]" multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf" class="hidden" onchange="showFileNames(this)">
                        <div id="fileList" class="mt-2 space-y-1"></div>
                        @error('attachments')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        @error('attachments.*')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary flex-1 justify-center py-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Aduan
                        </button>
                        <a href="{{ admin_route('complaints.index') }}" class="btn-outline px-6 py-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showFileNames(input) {
                const list = document.getElementById('fileList');
                list.innerHTML = '';
                Array.from(input.files).forEach(file => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2 text-xs text-slate-600 bg-slate-50 rounded-lg px-3 py-2';
                    div.innerHTML =
                        `<svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg> ${file.name} <span class="text-slate-400">(${(file.size/1024).toFixed(1)} KB)</span>`;
                    list.appendChild(div);
                });
            }
        </script>
    @endpush

</x-layouts.admin>
