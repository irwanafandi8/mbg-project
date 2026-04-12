<x-layouts.user :title="'Aduan #' . $complaint->complaint_number">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('user.complaints.index') }}" class="btn-outline btn-sm">
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
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Complaint Detail --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Detail Aduan</h3>
                    @if ($complaint->status->value === 'pending')
                        <div class="flex gap-2">
                            <a href="{{ route('user.complaints.edit', $complaint) }}" class="btn-warning btn-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('user.complaints.destroy', $complaint) }}"
                                onsubmit="return confirm('Hapus aduan ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <p class="text-xs text-slate-500">Judul</p>
                        <h4 class="font-semibold text-slate-800 text-lg mt-1">{{ $complaint->title }}</h4>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Deskripsi</p>
                        <p class="text-slate-700 leading-relaxed mt-1">{{ $complaint->description }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-xs text-slate-500">Dapur MBG</p>
                            <p class="font-medium text-slate-800">{{ $complaint->kitchen?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Kategori</p>
                            <p class="font-medium text-slate-800">{{ $complaint->category?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Prioritas</p>
                            <span
                                class="badge {{ $complaint->priority->bgClass() }}">{{ $complaint->priority->label() }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Tanggal Kirim</p>
                            <p class="font-medium text-slate-800">{{ $complaint->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attachments --}}
            @if ($complaint->attachments->count())
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-slate-800">Lampiran</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($complaint->attachments as $att)
                                @if ($att->is_image)
                                    <a href="{{ Storage::url($att->file_path) }}" target="_blank">
                                        <img src="{{ Storage::url($att->file_path) }}" alt="{{ $att->file_name }}"
                                            class="w-full h-28 object-cover rounded-xl border border-slate-200 hover:opacity-80 transition-opacity">
                                        <p class="text-xs text-slate-500 mt-1 truncate">{{ $att->file_name }}</p>
                                    </a>
                                @else
                                    <a href="{{ Storage::url($att->file_path) }}" target="_blank"
                                        class="flex flex-col items-center justify-center h-28 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors">
                                        <svg class="w-8 h-8 text-slate-400 mb-1" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-xs text-slate-500 truncate max-w-full px-2">
                                            {{ $att->file_name }}</p>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Responses Timeline --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Tanggapan Admin</h3>
                    <span class="text-xs text-slate-500">{{ $complaint->responses->count() }} tanggapan</span>
                </div>
                <div class="card-body">
                    @if ($complaint->responses->count())
                        <div>
                            @foreach ($complaint->responses as $response)
                                <div class="timeline-item">
                                    <div class="flex-1 bg-blue-50 rounded-xl p-4 border border-blue-100">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="font-semibold text-slate-800 text-sm">{{ $response->user->name }}</span>
                                            </div>
                                            <span
                                                class="text-xs text-slate-400">{{ $response->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-slate-700 text-sm leading-relaxed">{{ $response->message }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state py-8">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3>Belum ada tanggapan</h3>
                            <p>Admin sedang memproses aduan Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status Timeline --}}
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Alur Status</h3>
                </div>
                <div class="card-body">
                    @php
                        $flow = [
                            [
                                'status' => 'pending',
                                'label' => 'Menunggu',
                                'iconBg' => 'bg-yellow-100',
                                'iconText' => 'text-yellow-700',
                                'dot' => 'bg-yellow-500',
                                'currentText' => 'text-yellow-600',
                            ],
                            [
                                'status' => 'received',
                                'label' => 'Diterima',
                                'iconBg' => 'bg-blue-100',
                                'iconText' => 'text-blue-700',
                                'dot' => 'bg-blue-500',
                                'currentText' => 'text-blue-600',
                            ],
                            [
                                'status' => 'in_progress',
                                'label' => 'Diproses',
                                'iconBg' => 'bg-orange-100',
                                'iconText' => 'text-orange-700',
                                'dot' => 'bg-orange-500',
                                'currentText' => 'text-orange-600',
                            ],
                            [
                                'status' => 'resolved',
                                'label' => 'Selesai',
                                'iconBg' => 'bg-green-100',
                                'iconText' => 'text-green-700',
                                'dot' => 'bg-green-500',
                                'currentText' => 'text-green-600',
                            ],
                        ];
                        $currentStatus = $complaint->status->value;
                        $currentIndex = collect($flow)->search(function ($f) use ($currentStatus) {
                            return $f['status'] === $currentStatus;
                        });
                    @endphp
                    <div class="space-y-3">
                        @foreach ($flow as $idx => $step)
                            @php $done = $currentIndex !== false && $idx <= $currentIndex; @endphp
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $done ? $step['iconBg'] . ' ' . $step['iconText'] : 'bg-slate-100 text-slate-400' }}">
                                    @if ($done)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                                    @endif
                                </div>
                                <div>
                                    <p
                                        class="text-sm {{ $done ? 'font-semibold text-slate-800' : 'text-slate-400' }}">
                                        {{ $step['label'] }}
                                    </p>
                                    @if ($complaint->status->value === $step['status'])
                                        <p class="text-xs {{ $step['currentText'] }}">Status saat ini</p>
                                    @endif
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div class="ml-4 w-px h-3 bg-slate-200"></div>
                            @endif
                        @endforeach

                        @if ($complaint->status->value === 'rejected')
                            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center bg-red-100 text-red-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-red-600">Ditolak</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.user>
