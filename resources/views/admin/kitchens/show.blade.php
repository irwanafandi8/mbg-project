<x-layouts.admin :title="$kitchen->name">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.kitchens.index') }}" class="btn-outline btn-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        <span class="badge {{ $kitchen->operational_status->bgClass() }}">
            <span class="badge-dot {{ $kitchen->operational_status->dotClass() }}"></span>
            {{ $kitchen->operational_status->label() }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            {{-- Kitchen Information --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Informasi Dapur</h3>
                </div>
                <div class="card-body mt-4 space-y-4">
                    <div>
                        <p class="text-xs text-slate-500">Nama Dapur</p>
                        <p class="font-medium text-slate-800 mt-1">{{ $kitchen->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Penanggung Jawab</p>
                        <p class="font-medium text-slate-800 mt-1">{{ $kitchen->person_in_charge }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nomor Telepon</p>
                        <p class="font-medium text-slate-800 mt-1">{{ $kitchen->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Kapasitas Produksi</p>
                        <p class="font-medium text-slate-800 mt-1">
                            {{ number_format($kitchen->production_capacity, 0, ',', '.') }} porsi/hari</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Alamat</p>
                        <p class="font-medium text-slate-800 mt-1 leading-relaxed">{{ $kitchen->address }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.kitchens.edit', $kitchen) }}"
                            class="btn-warning w-full justify-center">
                            Edit Dapur
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            {{-- Schools Covered --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Sekolah Dilayani ({{ $kitchen->schools->count() }})</h3>
                </div>
                <div class="table-container border-0 rounded-none border-t border-slate-100">
                    <table class="data-table">
                        <thead class="bg-slate-50">
                            <tr>
                                <th>No</th>
                                <th>NPSN</th>
                                <th>Nama Sekolah</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($kitchen->schools as $index => $school)
                                <tr>
                                    <td class="text-slate-500">{{ $index + 1 }}</td>
                                    <td class="font-medium text-slate-800">{{ $school->npsn }}</td>
                                    <td>{{ $school->name }}</td>
                                    <td class="text-slate-500 truncate max-w-xs">{{ $school->address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-slate-400">Dapur ini belum melayani
                                        sekolah manapun.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Complaints --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Aduan Terkait ({{ $kitchen->complaints->count() }})</h3>
                </div>
                <div class="table-container border-0 rounded-none border-t border-slate-100">
                    <table class="data-table">
                        <thead class="bg-slate-50">
                            <tr>
                                <th>No</th>
                                <th>No. Aduan</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tgl Masuk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($kitchen->complaints->take(5) as $index => $complaint)
                                <tr>
                                    <td class="text-slate-500">{{ $index + 1 }}</td>
                                    <td class="font-medium text-blue-600">
                                        <a href="{{ route('admin.complaints.show', $complaint) }}">
                                            {{ $complaint->complaint_number }}
                                        </a>
                                    </td>
                                    <td>{{ $complaint->category?->name ?? 'Lainnya' }}</td>
                                    <td>
                                        <span class="badge {{ $complaint->status->bgClass() }} scale-90 origin-left">
                                            <span class="badge-dot {{ $complaint->status->dotClass() }}"></span>
                                            {{ $complaint->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-slate-500">{{ $complaint->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.complaints.show', $complaint) }}"
                                            class="text-blue-600 hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-slate-400">Belum ada aduan untuk
                                        dapur ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-slate-800">Saran Terkait ({{ $suggestions->count() }})</h3>
                </div>
                <div class="table-container border-0 rounded-none border-t border-slate-100">
                    <table class="data-table">
                        <thead class="bg-slate-50">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pengirim</th>
                                <th>Sekolah</th>
                                <th>Isi Saran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($suggestions as $index => $suggestion)
                                <tr>
                                    <td class="text-slate-500">{{ $index + 1 }}</td>
                                    <td class="text-slate-500 text-xs">
                                        {{ $suggestion->created_at->format('d M Y, H:i') }}</td>
                                    <td class="font-medium">{{ $suggestion->user->name }}</td>
                                    <td>{{ $suggestion->user->school?->name ?? '-' }}</td>
                                    <td class="text-slate-600 max-w-md truncate">{{ $suggestion->message }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-slate-400">Belum ada saran dari
                                        sekolah yang dilayani dapur ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
