<x-layouts.user title="Dashboard">

    {{-- Welcome Banner --}}
    <div class="bg-blue-800 rounded-2xl p-6 mb-6 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 w-40 h-40 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="relative">
            <p class="text-blue-200 text-sm mb-1">Selamat Datang Kembali</p>
            <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
            <p class="text-blue-200 text-sm font-semibold mt-1">{{ $school?->name ?? 'Sekolah belum dikonfigurasi' }}</p>
        </div>
    </div>

    @if (!$school || !$school->kitchen_id)
        <div class="alert-warning mb-6">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p>Sekolah Anda belum dipetakan ke dapur MBG. Hubungi administrator untuk melakukan pemetaan sebelum dapat
                membuat aduan.</p>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Total Aduan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalComplaints }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-yellow-100">
                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Menunggu</p>
                <p class="text-2xl font-bold text-slate-800">{{ $pendingComplaints }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-orange-100">
                <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Diproses</p>
                <p class="text-2xl font-bold text-slate-800">{{ $processedComplaints }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-100">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Selesai</p>
                <p class="text-2xl font-bold text-slate-800">{{ $resolvedComplaints }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Complaints --}}
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-slate-800">Aduan Terbaru</h3>
            <a href="{{ route('user.complaints.index') }}" class="btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Aduan</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-blue-600">
                                {{ $complaint->complaint_number }}
                            </td>
                            <td class="font-medium max-w-xs truncate">{{ $complaint->title }}</td>
                            <td class="text-slate-500 text-xs">{{ $complaint->category?->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $complaint->status->bgClass() }}">
                                    <span class="badge-dot {{ $complaint->status->dotClass() }}"></span>
                                    {{ $complaint->status->label() }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-500">{{ $complaint->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('user.complaints.show', $complaint) }}"
                                    class="btn-icon text-blue-600 hover:bg-blue-50">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state py-8">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3>Belum ada aduan</h3>
                                    <p>Klik "Buat Aduan" di atas untuk membuat pengaduan pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.user>
