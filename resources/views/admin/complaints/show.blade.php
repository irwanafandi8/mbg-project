<x-layouts.admin :title="'Aduan #' . $complaint->complaint_number">

    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('admin.complaints.index') }}" class="btn-outline btn-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        <span class="font-mono text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">
            {{ $complaint->complaint_number }}
        </span>

        <span class="badge {{ $complaint->status->bgClass() }}">
            <span class="badge-dot {{ $complaint->status->dotClass() }}"></span>
            {{ $complaint->status->label() }}
        </span>

        <span class="badge {{ $complaint->priority->bgClass() }}">
            {{ $complaint->priority->label() }}
        </span>
    </div>

    <div class="space-y-6">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2">
                <div class="card h-full">
                    <div class="card-header">
                        <h3 class="font-semibold text-slate-800">Detail Aduan</h3>
                        <span class="text-xs text-slate-500">{{ $complaint->created_at->format('d M Y, H:i') }}
                            WIB</span>
                    </div>
                    <div class="card-body space-y-4">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Judul Aduan</p>
                            <h4 class="font-semibold text-slate-800 text-lg">{{ $complaint->title }}</h4>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 mb-1">Deskripsi</p>
                            <p class="text-slate-700 leading-relaxed">{{ $complaint->description }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 mt-2 border-t border-slate-100">
                            <div>
                                <p class="text-xs text-slate-500">Pengadu (Sekolah)</p>
                                <p class="font-medium text-slate-800">
                                    {{ $complaint->user->school?->name ?? $complaint->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Dapur MBG</p>
                                <p class="font-medium text-slate-800">{{ $complaint->kitchen?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Kategori</p>
                                <p class="font-medium text-slate-800">{{ $complaint->category?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Diselesaikan Pada</p>
                                <p class="font-medium text-slate-800">
                                    {{ $complaint->resolved_at ? $complaint->resolved_at->format('d M Y, H:i') : 'Belum selesai' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card h-full">
                    <div class="card-header">
                        <h3 class="font-semibold text-slate-800">Informasi Pengadu</h3>
                    </div>
                    <div class="card-body space-y-3 text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Nama</span>
                            <span class="font-medium text-right">{{ $complaint->user->name }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Sekolah</span>
                            <span class="font-medium text-right">{{ $complaint->user->school?->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Email</span>
                            <span class="font-medium text-right text-blue-600">{{ $complaint->user->email }}</span>
                        </div>
                        <hr class="border-slate-100">
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Dapur MBG</span>
                            <span class="font-medium text-right">{{ $complaint->kitchen?->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">PIC Dapur</span>
                            <span
                                class="font-medium text-right">{{ $complaint->kitchen?->person_in_charge ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($complaint->attachments->count())
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Lampiran ({{ $complaint->attachments->count() }})</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach ($complaint->attachments as $attachment)
                            <div class="h-32">
                                @if ($attachment->is_image)
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                        class="block h-full cursor-pointer">
                                        <img src="{{ Storage::url($attachment->file_path) }}"
                                            alt="{{ $attachment->file_name }}"
                                            class="w-full h-full object-cover rounded-xl border border-slate-200 hover:opacity-90 transition-opacity">
                                        </p>
                                    </a>
                                @else
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                        class="flex flex-col items-center justify-center p-2 h-full bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors overflow-hidden">
                                        <svg class="w-10 h-10 text-slate-400 mb-2 shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-xs text-slate-500 truncate w-full text-center px-1 font-medium">
                                            {{ $attachment->file_name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider">
                                            {{ $attachment->formatted_size }}</p>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2">
                <div class="card h-full">
                    <div class="card-header">
                        <h3 class="font-semibold text-slate-800">Riwayat Tanggapan</h3>
                    </div>
                    <div class="card-body">
                        @if ($complaint->responses->count())
                            <div class="space-y-4">
                                @foreach ($complaint->responses as $response)
                                    <div class="flex gap-4">
                                        <div class="flex-1 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                            <div
                                                class="flex flex-wrap items-center justify-between gap-2 mb-2 pb-2 border-b border-slate-200">
                                                <p class="font-semibold text-slate-800 text-sm">
                                                    {{ $response->user->name }}</p>
                                                <p class="text-xs text-slate-500 font-medium">
                                                    {{ $response->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                            <p class="text-slate-700 text-sm leading-relaxed">{{ $response->message }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state py-8 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <h3 class="font-medium text-slate-800">Belum ada tanggapan</h3>
                                <p class="text-sm mt-1">Berikan tanggapan menggunakan form di samping.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="card h-full">
                    <div class="card-header">
                        <h3 class="font-semibold text-slate-800">Perbarui Status</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.complaints.update-status', $complaint) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label class="form-label">Status Aduan</label>
                                <select name="status" class="form-select cursor-pointer mt-1">
                                    @foreach ($statuses as $s)
                                        <option value="{{ $s->value }}"
                                            {{ $complaint->status === $s ? 'selected' : '' }}>
                                            {{ $s->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Tanggapan (opsional)</label>
                                <textarea name="response" rows="4" class="form-textarea mt-1" placeholder="Tulis tanggapan untuk pengadu..."></textarea>
                                @error('response')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="btn-primary w-full justify-center cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
