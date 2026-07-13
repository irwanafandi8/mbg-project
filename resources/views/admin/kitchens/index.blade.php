<x-layouts.admin title="Dapur MBG" breadcrumb="Daftar dapur MBG">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-indigo-100">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Total Dapur</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalKitchens }}</p>
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
                <p class="text-xs text-slate-500">Dapur Aktif</p>
                <p class="text-2xl font-bold text-slate-800">{{ $activeKitchens }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-red-100">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Tidak Aktif</p>
                <p class="text-2xl font-bold text-slate-800">{{ $inactiveKitchens }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header gap-3 flex-wrap">
            <h3 class="font-semibold text-slate-800">Data Dapur MBG</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <form method="GET" class="flex gap-2 flex-wrap">
                    <div class="search-bar">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input name="search" value="{{ request('search') }}" placeholder="Cari dapur..."
                            class="w-40">
                    </div>

                    <select name="status" class="form-select py-2 w-40" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach (\App\Enums\OperationalStatus::cases() as $s)
                            <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>
                                {{ $s->label() }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn-secondary btn-sm">Cari</button>
                </form>

                @if (auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.kitchens.create') }}" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Dapur
                </a>
                @endif
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dapur</th>
                        <th>Penanggung Jawab</th>
                        <th>Kapasitas Produksi</th>
                        <th>Sekolah</th>
                        <th>Status</th>
                        @if (auth()->user()->isSuperAdmin())
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($kitchens as $index => $kitchen)
                        <tr>
                            <td class="text-slate-500">{{ $kitchens->firstItem() + $index }}</td>
                            <td class="font-semibold whitespace-nowrap">{{ $kitchen->name }}</td>
                            <td>{{ $kitchen->person_in_charge }}</td>
                            <td>
                                <span class="font-semibold">{{ number_format($kitchen->production_capacity) }}</span>
                                <span class="text-xs text-slate-400">porsi/hari</span>
                            </td>
                            <td>
                                <span class="font-semibold text-blue-600">{{ $kitchen->schools_count }}</span>
                                <span class="text-xs text-slate-400">sekolah</span>
                            </td>
                            <td>
                                <span class="badge {{ $kitchen->operational_status->bgClass() }}">
                                    {{ $kitchen->operational_status->label() }}
                                </span>
                            </td>
                            @if (auth()->user()->isSuperAdmin())
                            <td class="flex items-center gap-1">
                                <a href="{{ route('admin.kitchens.show', $kitchen) }}"
                                    class="btn-icon text-blue-600 hover:bg-blue-50" title="Detail">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.kitchens.edit', $kitchen) }}"
                                    class="btn-icon text-amber-600 hover:bg-amber-50" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.kitchens.destroy', $kitchen) }}"
                                    onsubmit="return confirm('Hapus dapur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-red-500 hover:bg-red-50"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isSuperAdmin() ? 8 : 7 }}">
                                <div class="empty-state">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                    </svg>
                                    <h3>Belum ada dapur</h3>
                                    @if (auth()->user()->isSuperAdmin())
                                    <p>Klik tombol Tambah Dapur untuk membuat data baru.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kitchens->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $kitchens->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
