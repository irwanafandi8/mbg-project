<x-layouts.admin title="Sekolah" breadcrumb="Daftar sekolah">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-cyan-100">
                <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Total Sekolah</p>
                <p class="text-2xl font-bold">{{ $totalSchools }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-emerald-100">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Sudah Dipetakan</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $mappedSchools }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-amber-100">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Belum Dipetakan</p>
                <p class="text-2xl font-bold text-amber-600">{{ $unmappedSchools }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header gap-3 flex-wrap">
            <h3 class="font-semibold text-slate-800">Data Sekolah</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <form method="GET" class="flex gap-2 flex-wrap">
                    <div class="search-bar">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input name="search" value="{{ request('search') }}" placeholder="Cari nama/NPSN..."
                            class="w-44">
                    </div>
                    <select name="kitchen_id" class="form-select py-2 w-44" onchange="this.form.submit()">
                        <option value="">Semua Dapur</option>
                        @foreach ($kitchens as $k)
                            <option value="{{ $k->id }}" {{ request('kitchen_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn-secondary btn-sm">Cari</button>
                </form>

                @if (auth()->user()->isSuperAdmin())
                <a href="{{ admin_route('schools.create') }}" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Sekolah
                </a>
                @endif
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Sekolah</th>
                        <th>NPSN</th>
                        <th>Dapur MBG</th>
                        <th>Status</th>
                        @if (auth()->user()->isSuperAdmin())
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $index => $school)
                        <tr>
                            <td class="text-slate-500">{{ $schools->firstItem() + $index }}</td>
                            <td class="font-semibold whitespace-nowrap">{{ $school->name }}</td>
                            <td class="font-mono text-xs text-slate-500">{{ $school->npsn }}</td>
                            <td class="px-4 text-xs whitespace-nowrap">
                                @if ($school->kitchen)
                                    <span
                                        class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-xs font-medium">
                                        {{ $school->kitchen->name }}
                                    </span>
                                @else
                                    <span
                                        class="inline-block bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-xs font-medium">
                                        Belum dipetakan
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge {{ $school->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $school->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            @if (auth()->user()->isSuperAdmin())
                            <td class="flex items-center gap-1">
                                <a href="{{ admin_route('schools.edit', $school) }}"
                                    class="btn-icon text-amber-600 hover:bg-amber-50" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ admin_route('schools.destroy', $school) }}"
                                    onsubmit="return confirm('Hapus sekolah ini?')">
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
                            <td colspan="{{ auth()->user()->isSuperAdmin() ? 7 : 6 }}">
                                <div class="empty-state">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 14l9-5-9-5-9 5 9 5z" />
                                    </svg>
                                    <h3>Belum ada sekolah</h3>
                                    @if (auth()->user()->isSuperAdmin())
                                    <p>Klik tombol Tambah Sekolah untuk membuat data baru.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($schools->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $schools->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
