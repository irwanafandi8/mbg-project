<x-layouts.user title="Saran & Masukan">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2 text-xs">
            <span class="badge bg-slate-100 text-slate-700">Total: {{ $suggestions->total() }}</span>
            <span class="badge bg-emerald-100 text-emerald-700">Sudah Dibaca: {{ $readCount }}</span>
        </div>
        <a href="{{ route('user.suggestions.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Kirim Saran
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse($suggestions as $suggestion)
            <div class="card h-full">
                <div class="card-body">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <!-- Avatar -->
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 
                    flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <!-- User Info -->
                            <div>
                                <p class="text-sm font-semibold text-slate-800 leading-tight">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $suggestion->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <!-- Badge -->
                        <span
                            class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium
        {{ $suggestion->is_read
            ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
            : 'bg-blue-100 text-blue-700 ring-1 ring-blue-200 animate-pulse' }}">

                            {{ $suggestion->is_read ? 'Dibaca' : 'Baru' }}
                        </span>
                    </div>
                    <p class="text-slate-700 leading-relaxed">{{ $suggestion->message }}</p>
                </div>
            </div>
        @empty
            <div class="card lg:col-span-2">
                <div class="card-body">
                    <div class="empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <h3>Belum ada saran</h3>
                        <p>Sampaikan saran Anda untuk membantu meningkatkan layanan MBG.</p>
                        <a href="{{ route('user.suggestions.create') }}" class="btn-primary mt-4">Kirim Saran
                            Pertama</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($suggestions->hasPages())
        <div class="mt-4">{{ $suggestions->links() }}</div>
    @endif

</x-layouts.user>
