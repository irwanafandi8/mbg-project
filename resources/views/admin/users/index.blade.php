<x-layouts.admin title="Manajemen Pengguna" breadcrumb="Kelola akun admin dan administrator sekolah">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-indigo-100">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.121 17.804A9 9 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Admin Sistem</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalAdmins }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-cyan-100">
                <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356-1.857" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Administrator Sekolah</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalSchoolAdmins }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header gap-3 flex-wrap">
            <h3 class="font-semibold text-slate-800">Daftar Pengguna</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <form method="GET" class="flex gap-2 flex-wrap">
                    <div class="search-bar">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input name="search" value="{{ request('search') }}" placeholder="Cari nama/email..."
                            class="w-44">
                    </div>

                    <select name="role" class="form-select py-2 w-44" onchange="this.form.submit()">
                        <option value="">Semua Kelompok</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin Sistem</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Administrator Sekolah
                        </option>
                    </select>

                    <select name="school_id" class="form-select py-2 w-44" onchange="this.form.submit()">
                        <option value="">Semua Sekolah</option>
                        @foreach ($schools as $s)
                            <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}</option>
                        @endforeach
                    </select>

                    <button class="btn-secondary btn-sm">Cari</button>
                </form>

                <a href="{{ route('admin.users.create-admin') }}" class="btn-secondary btn-sm">Tambah Admin</a>
                <a href="{{ route('admin.users.create') }}" class="btn-primary btn-sm">Tambah Administrator Sekolah</a>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Kelompok</th>
                        <th>Sekolah</th>
                        <th>No. Telepon</th>
                        <th>Login Terakhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="text-slate-500">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="font-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-slate-500">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-4 text-xs">
                                @if ($user->role->value === 'admin')
                                    <span class="badge bg-indigo-100 text-indigo-700">Admin Sistem</span>
                                @else
                                    <span class="badge bg-slate-100 text-slate-700">Administrator Sekolah</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">{{ $user->school?->name ?? '-' }}</td>
                            <td class="text-slate-500">{{ $user->phone ?? '-' }}</td>
                            <td class="text-xs text-slate-500">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                            </td>
                            <td>
                                <span
                                    class="badge {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="flex items-center gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="btn-icon text-amber-600 hover:bg-amber-50" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn-icon {{ $user->is_active ? 'text-slate-500 hover:bg-slate-100' : 'text-emerald-600 hover:bg-emerald-50' }}"
                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Hapus pengguna ini?')">
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356-1.857" />
                                    </svg>
                                    <h3>Belum ada pengguna</h3>
                                    <p>Tambahkan admin atau administrator sekolah.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $users->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
