<x-layouts.admin title="Log Aktivitas Sistem">

    <div class="card">
        <div class="card-header gap-2 flex-wrap">
            <h3 class="font-semibold text-slate-800">Audit Log</h3>
            <form method="GET" class="flex items-center gap-2 flex-wrap">
                <div class="search-bar">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari aksi..." class="w-44">
                </div>
                <input type="date" name="date" value="{{ request('date') }}" placeholder="Pilih tanggal"
                    class="form-input py-2 w-36">
                <button class="btn btn-secondary btn-sm">Filter</button>
                @if (request()->hasAny(['search', 'date']))
                    <a href="{{ admin_route('audit-logs.index') }}" class="btn-outline btn-sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aksi</th>
                        <th>Model</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr>
                            <td class="text-slate-500">{{ $logs->firstItem() + $index }}</td>
                            <td class="text-xs text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('d M Y, H:i:s') }}
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="whitespace-nowrap">
                                        <p class="text-sm font-medium">{{ $log->user?->name ?? 'System' }}</p>
                                        <p class="text-xs text-slate-400">{{ $log->user?->role?->label() ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-500">
                                @if ($log->model_type)
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="font-mono text-xs text-slate-500">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3>Belum ada log</h3>
                                    <p>Log aktivitas akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $logs->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
