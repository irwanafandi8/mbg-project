<x-layouts.admin title="Dashboard">

    {{-- Welcome Banner --}}
    <div class="bg-blue-800 rounded-2xl p-6 mb-6 text-white flex items-center justify-between overflow-hidden relative">
        <div class="absolute right-0 top-0 w-48 h-48 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="relative">
            <span class="text-blue-200 text-sm mb-1">Selamat Datang Kembali, Admin</span>
            <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
            <p class="text-blue-200 text-sm font-semibold mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="relative hidden md:block">
            <div class="text-right">
                <p class="text-blue-200 text-sm">Waktu Rata-rata Penyelesaian</p>
                <p class="text-3xl font-bold">{{ $avgResolution }} Jam</p>
            </div>
        </div>
    </div>

    {{-- Stat Cards Row 1 : Complaints --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Total Aduan</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['total']) }}</p>
                <a href="{{ route('admin.complaints.index') }}" class="text-xs text-blue-600 hover:underline">Lihat
                    semua</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-yellow-100">
                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Belum Ditangani</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['unresolved']) }}</p>
                <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}"
                    class="text-xs text-yellow-600 hover:underline">Lihat</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-orange-100">
                <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Diproses</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['in_progress']) }}</p>
                <a href="{{ route('admin.complaints.index', ['status' => 'in_progress']) }}"
                    class="text-xs text-orange-600 hover:underline">Lihat</a>
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
                <p class="text-xs text-slate-500 font-medium">Selesai</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['resolved']) }}</p>
                <a href="{{ route('admin.complaints.index', ['status' => 'resolved']) }}"
                    class="text-xs text-emerald-600 hover:underline">Lihat</a>
            </div>
        </div>
    </div>

    {{-- Stat Cards Row 2 : Users/Schools/Kitchens --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-cyan-100">
                <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Total Sekolah</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalSchools }}</p>
                <a href="{{ route('admin.schools.index') }}" class="text-xs text-cyan-600 hover:underline">Kelola</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-indigo-100">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Total Dapur MBG</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalKitchens }}</p>
                <a href="{{ route('admin.kitchens.index') }}"
                    class="text-xs text-indigo-600 hover:underline">Kelola</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-pink-100">
                <svg class="w-6 h-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Total Pengguna</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalUsers }}</p>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-pink-600 hover:underline">Kelola</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-amber-100">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Jumlah Saran</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalSuggestions }}</p>
                <a href="{{ route('admin.suggestions.index') }}"
                    class="text-xs text-amber-600 hover:underline">Kelola</a>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Monthly Line Chart --}}
        <div class="card md:col-span-2">
            <div class="card-header">
                <div>
                    <h3 class="font-semibold text-slate-800">Statistik Aduan Bulanan</h3>
                    <p class="text-xs text-slate-500">{{ now()->year }}</p>
                </div>
            </div>
            <div class="card-body">
                <div class="relative h-64 w-full">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Category Donut Chart --}}
        <div class="card flex flex-col">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Statistik per Kategori</h3>
            </div>
            <div class="card-body flex-1 flex flex-col">
                <div class="relative h-48 w-full flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach ($byCategory->take(5) as $cat)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600 truncate max-w-32">{{ $cat->name }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-24 h-1.5 bg-slate-100 rounded-full">
                                    @php $pct = $stats['total'] > 0 ? ($cat->complaints_count / $stats['total']) * 100 : 0; @endphp
                                    <div class="h-1.5 bg-blue-500 rounded-full" style="width: {{ $pct }}%">
                                    </div>
                                </div>
                                <span class="text-slate-500 text-xs w-8 text-right">{{ number_format($pct) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Complaints Table --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="font-semibold text-slate-800">Aduan Terbaru</h3>
                <p class="text-xs text-slate-500">3 aduan terakhir yang masuk</p>
            </div>
            <a href="{{ route('admin.complaints.index') }}" class="btn-outline btn-sm">
                Lihat Semua
            </a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Aduan</th>
                        <th>Sekolah</th>
                        <th>Dapur MBG</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentComplaints as $index => $complaint)
                        <tr>
                            <td class="text-slate-500">{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint) }}"
                                    class="font-mono text-blue-600 hover:underline text-xs font-semibold">
                                    {{ $complaint->complaint_number }}
                                </a>
                            </td>
                            <td class="font-medium">{{ $complaint->user->school?->name ?? '-' }}</td>
                            <td class="text-slate-500">{{ $complaint->kitchen?->name ?? '-' }}</td>
                            <td>{{ $complaint->category?->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $complaint->status->bgClass() }}">
                                    <span class="badge-dot {{ $complaint->status->dotClass() }}"></span>
                                    {{ $complaint->status->label() }}
                                </span>
                            </td>
                            <td class="text-slate-500 text-xs">{{ $complaint->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">Belum ada aduan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Monthly Line Chart
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Jumlah Aduan',
                        data: @json($chartData),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.08)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        }
                    }
                }
            });

            // Category Donut Chart
            @php
                $catLabels = $byCategory->pluck('name')->toArray();
                $catData = $byCategory->pluck('complaints_count')->toArray();
                $catColors = ['#3b82f6', '#60a5fa', '#93c5fd', '#0ea5e9', '#06b6d4', '#10b981', '#f59e0b', '#ef4444'];
            @endphp
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($catLabels),
                    datasets: [{
                        data: @json(count($catData) ? $catData : [1]),
                        backgroundColor: @json($catColors),
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush

</x-layouts.admin>
