<x-layouts.user title="Aduan Saya">

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-slate-800">Daftar Aduan</h3>
            <div class="flex items-center gap-2">
                <form method="GET" class="flex gap-2">
                    <select name="status" class="form-select py-2 w-32 md:w-36" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $s)
                            <option class="text-sm md:text-base" value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('user.complaints.create') }}" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Aduan</th>
                        <th>Judul Aduan</th>
                        <th>Dapur MBG</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $index => $complaint)
                        <tr>
                            <td class="text-slate-500">{{ $complaints->firstItem() + $index }}</td>
                            <td class="font-mono text-xs font-semibold text-blue-600">{{ $complaint->complaint_number }}
                            </td>
                            <td class="font-medium max-w-xs">
                                <p class="truncate">{{ $complaint->title }}</p>
                            </td>
                            <td class="text-slate-500 text-sm">{{ $complaint->kitchen?->name ?? '-' }}</td>
                            <td class="text-slate-500 text-sm">{{ $complaint->category?->name ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge {{ $complaint->priority->bgClass() }}">{{ $complaint->priority->label() }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $complaint->status->bgClass() }}">
                                    <span class="badge-dot {{ $complaint->status->dotClass() }}"></span>
                                    {{ $complaint->status->label() }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-500 whitespace-nowrap">
                                {{ $complaint->created_at->format('d M Y') }}</td>
                            <td class="flex items-center gap-1">
                                <a href="{{ route('user.complaints.show', $complaint) }}"
                                    class="btn-icon text-blue-600 hover:bg-blue-50" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if ($complaint->status->value === 'pending')
                                    <a href="{{ route('user.complaints.edit', $complaint) }}"
                                        class="btn-icon text-amber-600 hover:bg-amber-50" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('user.complaints.destroy', $complaint) }}"
                                        onsubmit="return confirm('Hapus aduan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-500 hover:bg-red-50"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
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
                                    <h3>Belum ada aduan</h3>
                                    <p>Anda belum pernah membuat pengaduan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($complaints->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $complaints->links() }}</div>
        @endif
    </div>

</x-layouts.user>
