<x-layouts.app :title="$title ?? ''">
    <div class="flex h-full min-h-screen">
        {{-- Sidebar --}}
        <aside class="sidebar -translate-x-full lg:translate-x-0 transition-transform duration-300" id="sidebar">
            {{-- Logo --}}
            <div class="flex items-center gap-2 px-5 py-5 border-b border-white/10">
                <img src="{{ asset('images/logo-bgn.png') }}" class="h-12 w-12" alt="logo-bgn">
                <div>
                    <p class="text-white font-bold text-sm leading-tight">SPPG Indramayu Karanganyar 2</p>
                    <p class="text-blue-200 text-xs">Administrator</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-4 overflow-y-auto">
                <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider px-6 mb-2">Menu Utama</p>

                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @endif

                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.complaints.index') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.complaints.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Aduan</span>
                        @php $pendingCount = \App\Models\Complaint::where('status','pending')->count(); @endphp
                        @if ($pendingCount > 0)
                            <span
                                class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('admin.complaints.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Aduan</span>
                        @php $pendingCount = \App\Models\Complaint::where('status','pending')->count(); @endphp
                        @if ($pendingCount > 0)
                            <span
                                class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </a>
                @endif

                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.categories.index') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.categories.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.585l7 7a2 2 0 010 2.83l-7 7a2 2 0 01-2.83 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Kategori Aduan</span>
                    </a>
                @else
                    <a href="{{ route('admin.categories.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.585l7 7a2 2 0 010 2.83l-7 7a2 2 0 01-2.83 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Kategori Aduan</span>
                    </a>
                @endif

                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.schools.index') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.schools.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        <span>Sekolah</span>
                    </a>
                @else
                    <a href="{{ route('admin.schools.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.schools.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        <span>Sekolah</span>
                    </a>
                @endif
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.admins.index') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.admins.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.121 17.804A9 9 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Admin Sekolah</span>
                    </a>
                @endif

                {{-- User Management --}}
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.users.index') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.users.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Pengguna</span>
                    </a>
                @else
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Pengguna</span>
                    </a>
                @endif

                <div class="my-3 mx-4 border-t border-white/10"></div>
                <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider px-6 mb-2">Lainnya</p>

                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.suggestions.index') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.suggestions.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Saran</span>
                        @php $unreadSuggestions = \App\Models\Suggestion::unread()->count(); @endphp
                        @if ($unreadSuggestions > 0)
                            <span
                                class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadSuggestions }}</span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('admin.suggestions.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.suggestions.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Saran</span>
                        @php $unreadSuggestions = \App\Models\Suggestion::unread()->count(); @endphp
                        @if ($unreadSuggestions > 0)
                            <span
                                class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadSuggestions }}</span>
                        @endif
                    </a>
                @endif

                {{-- Super Admin only: Audit Logs --}}
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.audit-logs.index') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.audit-logs.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                @endif

                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.profile.edit') }}"
                        class="sidebar-link {{ request()->routeIs('super_admin.profile.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil</span>
                    </a>
                @else
                    <a href="{{ route('admin.profile.edit') }}"
                        class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil</span>
                    </a>
                @endif
            </nav>

            {{-- User info & logout --}}
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-3 px-2">
                    <div
                        class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center text-white font-semibold text-sm shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-blue-300 text-xs truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-4 py-2 rounded-lg text-red-300 hover:bg-red-500/20 hover:text-red-100 text-sm font-medium transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64 transition-all duration-300">
            {{-- Topbar --}}
            <header class="topbar">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle"
                        class="btn-icon text-slate-500 hover:text-slate-700 hover:bg-slate-100 lg:hidden">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-slate-800">{{ $title ?? 'Dashboard' }}</h1>
                        @isset($breadcrumb)
                            <p class="text-xs text-slate-500">{{ $breadcrumb }}</p>
                        @endisset
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-4 sm:px-6 pt-4">
                @if (session('success'))
                    <div class="alert-success animate-fade-in mb-4" id="flash-success">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert-error animate-fade-in mb-4">
                        <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 px-4 sm:px-6 py-4 animate-fade-in">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="px-4 sm:px-6 py-3 border-t border-slate-200 text-xs text-slate-400">
                &copy; {{ date('Y') }} SPPG Indramayu Karanganyar 2 — Sistem Pengaduan Program Makan Bergizi Gratis
            </footer>
        </div>

        <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/40 z-40 hidden lg:hidden"></div>
    </div>

    @stack('modals')

    <script>
        // Auto-hide success flash
        const flash = document.getElementById('flash-success');
        if (flash) setTimeout(() => flash.style.display = 'none', 5000);

        // Mobile sidebar toggle
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            backdrop?.classList.add('hidden');
        };

        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                backdrop?.classList.toggle('hidden');
            });
        }

        backdrop?.addEventListener('click', closeSidebar);
    </script>
    @stack('scripts')
</x-layouts.app>
