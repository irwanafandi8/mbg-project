<x-layouts.admin title="Manajemen Pengaduan">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
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
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalComplaints) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-red-100">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Belum Ditangani</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($pendingComplaints) }}</p>
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
                <p class="text-xs text-slate-500">Sudah Diselesaikan</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($resolvedComplaints) }}</p>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="card-header gap-2 flex-wrap">
            <div>
                <h3 class="font-semibold text-slate-800">Data Aduan</h3>
            </div>
            {{-- Filters --}}
            <form method="GET" class="flex items-center gap-2 flex-wrap">
                <div class="search-bar">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari nomor / judul..."
                        class="w-52">
                </div>
                <select name="status" class="form-select w-36 py-2" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach (\App\Enums\ComplaintStatus::cases() as $s)
                        <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>
                            {{ $s->label() }}
                        </option>
                    @endforeach
                </select>
                <select name="kitchen_id" class="form-select w-40 py-2" onchange="this.form.submit()">
                    <option value="">Semua Dapur</option>
                    @foreach ($kitchens as $kitchen)
                        <option value="{{ $kitchen->id }}"
                            {{ request('kitchen_id') == $kitchen->id ? 'selected' : '' }}>
                            {{ $kitchen->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary btn-sm">Cari</button>
                @if (request()->hasAny(['search', 'status', 'kitchen_id', 'category_id']))
                    <a href="{{ admin_route('complaints.index') }}" class="btn-outline btn-sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Tanggal & Waktu</th>
                        <th>Nama Pengadu (Sekolah)</th>
                        <th>Dapur MBG</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $index => $complaint)
                        <tr>
                            <td class="text-slate-500">{{ $complaints->firstItem() + $index }}</td>
                            <td>
                                <span
                                    class="font-mono text-xs font-semibold text-slate-500">{{ $complaint->complaint_number }}</span>
                            </td>
                            <td class="text-xs text-slate-500 whitespace-nowrap">
                                {{ $complaint->created_at->format('d-m-Y, H:i') }} WIB
                            </td>
                            <td class="font-medium">{{ $complaint->user->school?->name ?? $complaint->user->name }}
                            </td>
                            <td class="text-slate-500">{{ $complaint->kitchen?->name ?? '-' }}</td>
                            <td>{{ $complaint->category?->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $complaint->priority->bgClass() }}">
                                    {{ $complaint->priority->label() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $complaint->status->bgClass() }}">
                                    {{ $complaint->status->label() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ admin_route('complaints.show', $complaint) }}"
                                    class="btn-icon text-blue-600 hover:bg-blue-50" title="Lihat Detail">
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
                            <td colspan="9">
                                <div class="empty-state">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3>Tidak ada aduan</h3>
                                    <p>Belum ada aduan yang masuk sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($complaints->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

</x-layouts.admin>
