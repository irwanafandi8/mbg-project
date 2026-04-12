<x-layouts.user title="Kirim Saran" breadcrumb="Sampaikan saran atau masukan untuk meningkatkan layanan MBG">

    <div>
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('user.suggestions.index') }}" class="btn-outline btn-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Formulir Saran</h3>
            </div>
            <div class="card-body">
                <div class="alert-info mb-6">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm">Saran bersifat teks dan akan dibaca oleh administrator. Untuk keluhan yang
                        memerlukan tindakan, gunakan fitur <a href="{{ route('user.complaints.create') }}"
                            class="text-blue-700 font-medium hover:underline">Buat Aduan</a>.</p>
                </div>

                <form method="POST" action="{{ route('user.suggestions.store') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="form-label">Pesan Saran <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="7" placeholder="Tuliskan saran, masukan, atau ide perbaikan layanan MBG di sini..."
                            class="form-textarea {{ $errors->has('message') ? 'error' : '' }}">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="form-hint">Minimal 10 karakter, maksimal 2000 karakter</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary flex-1 justify-center py-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Saran
                        </button>
                        <a href="{{ route('user.suggestions.index') }}" class="btn-outline px-6">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.user>
