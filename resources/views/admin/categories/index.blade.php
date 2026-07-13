<x-layouts.admin title="Kategori Aduan" breadcrumb="Daftar kategori jenis pengaduan">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-emerald-100">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Kategori Aktif</p>
                <p class="text-2xl font-bold text-slate-800">{{ $activeCategories }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-slate-100">
                <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Kategori Nonaktif</p>
                <p class="text-2xl font-bold text-slate-800">{{ $inactiveCategories }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header gap-3 flex-wrap">
            <h3 class="font-semibold text-slate-800">Daftar Kategori</h3>

            <div class="flex items-center gap-2 flex-wrap">
                <form method="GET" class="search-bar">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="w-56">
                </form>

                @if (auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.categories.create') }}" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kategori
                </a>
                @endif
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Aduan</th>
                        <th>Deskripsi Singkat</th>
                        <th>Status</th>
                        @if (auth()->user()->isSuperAdmin())
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                        <tr>
                            <td class="text-slate-500">{{ $categories->firstItem() + $index }}</td>
                            <td class="font-medium">{{ $category->name }}</td>
                            <td>
                                <span class="font-semibold text-blue-600">{{ $category->complaints_count }}</span>
                                <span class="text-xs text-slate-400">aduan</span>
                            </td>
                            <td class="text-slate-500 max-w-xs truncate">{{ $category->description ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            @if (auth()->user()->isSuperAdmin())
                            <td class="flex items-center gap-1">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="btn-icon text-blue-600 hover:bg-blue-50" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn-icon {{ $category->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }}"
                                        title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                        </svg>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                    onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-red-500 hover:bg-red-50" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
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
                            <td colspan="{{ auth()->user()->isSuperAdmin() ? 6 : 5 }}">
                                <div class="empty-state">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.585l7 7a2 2 0 010 2.83l-7 7a2 2 0 01-2.83 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <h3>Belum ada kategori</h3>
                                    @if (auth()->user()->isSuperAdmin())
                                    <p>Klik tombol Tambah Kategori untuk membuat kategori baru.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $categories->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
