<x-layouts.admin title="Saran & Masukan" breadcrumb="Baca saran yang dikirimkan oleh sekolah">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-slate-100">
                <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Jumlah Saran</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalCount }}</p>
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
                <p class="text-xs text-slate-500">Sudah Dibaca</p>
                <p class="text-2xl font-bold text-slate-800">{{ $readCount }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Belum Dibaca</p>
                <p class="text-2xl font-bold text-slate-800">{{ $unreadCount }}</p>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <h3 class="font-semibold text-slate-800">Daftar Saran</h3>
                @if (!auth()->user()->isSuperAdmin())
                <a href="{{ admin_route('suggestions.create') }}" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Kirim Saran
                </a>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <form method="GET" class="flex gap-2">
                    <select name="status" class="form-select py-2" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca
                        </option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca
                        </option>
                    </select>
                </form>

                @if ($unreadCount > 0)
                    <form method="POST" action="{{ admin_route('suggestions.mark-all-read') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg 
               bg-blue-600 text-white text-sm font-medium
               hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none
               transition">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse($suggestions as $index => $suggestion)
            <div class="card h-full {{ !$suggestion->is_read ? 'ring-1 ring-blue-100' : '' }}">
                <div class="card-body">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm shrink-0">
                                {{ substr($suggestion->user->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $suggestion->user->name }}
                                </p>
                                <p class="text-xs text-slate-500 truncate">
                                    {{ $suggestion->user->school?->name ?? 'Sekolah tidak diketahui' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-slate-400">{{ $suggestion->created_at->format('d M Y, H:i') }}</p>
                            <span
                                class="badge mt-1 {{ $suggestion->is_read ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-700' }}">
                                {{ $suggestion->is_read ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                            </span>
                        </div>
                    </div>

                    <p class="text-slate-700 leading-relaxed mb-3">{{ $suggestion->message }}</p>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs text-slate-400">#{{ $suggestions->firstItem() + $index }}</span>
                        @if (!$suggestion->is_read)
                            <form method="POST" action="{{ admin_route('suggestions.mark-read', $suggestion) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai sudah
                                    dibaca</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card lg:col-span-2">
                <div class="card-body">
                    <div class="empty-state py-16">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <h3>Belum ada saran</h3>
                        <p>Saran dari sekolah akan muncul di sini.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($suggestions->hasPages())
        <div class="mt-4">{{ $suggestions->links() }}</div>
    @endif

</x-layouts.admin>
