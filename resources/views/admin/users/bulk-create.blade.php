<x-layouts.admin title="Tambah Pengguna Massal">

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ admin_route('users.index') }}" class="btn-outline btn-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if (!auth()->user()->isSuperAdmin())
        <div class="alert-info mb-6">
            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm">Pengguna akan ditambahkan ke sekolah: <span class="font-semibold">{{ $school->name ?? '-' }}</span></p>
        </div>
        @endif

        {{-- CSV Upload Section --}}
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Upload File CSV</h3>
            </div>
            <div class="card-body">
                <p class="text-sm text-slate-600 mb-4">Upload file CSV atau Excel (.xlsx/.xls) untuk mengisi data pengguna secara otomatis. Format kolom yang diperlukan:</p>
                <div class="bg-slate-50 rounded-lg p-3 mb-4 text-sm font-mono">
                    <span class="text-slate-500">Contoh format:</span><br>
                    <span class="text-slate-800">name,email,phone</span><br>
                    <span class="text-slate-800">Andi Saputra,andi@contoh.com,081234567890</span><br>
                    <span class="text-slate-800">Siti Rahmawati,siti@contoh.com,081298765432</span>
                </div>
                <ul class="text-xs text-slate-500 mb-4 space-y-1">
                    <li><span class="font-semibold">name</span> — Wajib diisi</li>
                    <li><span class="font-semibold">email</span> — Wajib diisi, harus valid</li>
                    <li><span class="font-semibold">phone</span> — Opsional</li>
                </ul>
                <a href="{{ admin_route('users.bulk-template') }}" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700 mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Template CSV
                </a>

                @if (!empty($errors))
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <p class="text-sm font-semibold text-red-700 mb-2">Data tidak valid:</p>
                    <ul class="text-xs text-red-600 space-y-1">
                        @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ admin_route('users.bulk-upload') }}" enctype="multipart/form-data" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="form-label">Pilih File</label>
                        <input type="file" name="csv_file" accept=".csv,.txt,.xlsx,.xls" required
                            class="form-input w-full md:w-96 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="form-hint">Format yang didukung: CSV (.csv), Excel (.xlsx, .xls). Maks 5MB.</p>
                    </div>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Upload &amp; Isi Form
                    </button>
                </form>
            </div>
        </div>

        {{-- Manual Form --}}
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Form Tambah Pengguna Massal (Siswa/Orang Tua)</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ admin_route('users.bulk') }}" id="bulkForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Password Default</label>
                        <input type="text" name="default_password" value="password123"
                            class="form-input w-full md:w-64" placeholder="Minimal 8 karakter">
                        <p class="form-hint">Semua user akan mendapat password ini. Kosongkan untuk default: password123</p>
                    </div>

                    <div class="overflow-x-auto mb-4">
                        <table class="data-table" id="userTable">
                            <thead>
                                <tr>
                                    <th class="w-12">No</th>
                                    <th class="w-60">Nama Lengkap *</th>
                                    <th class="w-60">Email *</th>
                                    <th class="w-40">No. Telepon</th>
                                    <th class="w-12"></th>
                                </tr>
                            </thead>
                            <tbody id="userRows">
                                @if (!empty($rows))
                                    @foreach ($rows as $i => $row)
                                    <tr class="user-row">
                                        <td class="text-slate-500 row-number">{{ $i + 1 }}</td>
                                        <td>
                                            <input type="text" name="users[{{ $i }}][name]" required
                                                class="form-input py-2 text-sm" placeholder="Nama lengkap"
                                                value="{{ $row['name'] }}">
                                        </td>
                                        <td>
                                            <input type="email" name="users[{{ $i }}][email]" required
                                                class="form-input py-2 text-sm" placeholder="email@contoh.com"
                                                value="{{ $row['email'] }}">
                                        </td>
                                        <td>
                                            <input type="text" name="users[{{ $i }}][phone]"
                                                class="form-input py-2 text-sm" placeholder="08xxx"
                                                value="{{ $row['phone'] }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn-icon text-red-500 hover:bg-red-50 remove-row" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr class="user-row">
                                    <td class="text-slate-500 row-number">1</td>
                                    <td>
                                        <input type="text" name="users[0][name]" required
                                            class="form-input py-2 text-sm" placeholder="Nama lengkap">
                                    </td>
                                    <td>
                                        <input type="email" name="users[0][email]" required
                                            class="form-input py-2 text-sm" placeholder="email@contoh.com">
                                    </td>
                                    <td>
                                        <input type="text" name="users[0][phone]"
                                            class="form-input py-2 text-sm" placeholder="08xxx">
                                    </td>
                                    <td>
                                        <button type="button" class="btn-icon text-red-500 hover:bg-red-50 remove-row" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="addRow" class="btn-outline btn-sm mb-6">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Baris
                    </button>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ admin_route('users.index') }}" class="btn-outline">Batal</a>
                        <button type="submit" class="btn-primary" id="submitBtn">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Semua
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let rowIndex = {{ !empty($rows) ? count($rows) : 1 }};

        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.getElementById('userRows');
            const tr = document.createElement('tr');
            tr.className = 'user-row';
            tr.innerHTML = `
                <td class="text-slate-500 row-number">${rowIndex + 1}</td>
                <td>
                    <input type="text" name="users[${rowIndex}][name]" required
                        class="form-input py-2 text-sm" placeholder="Nama lengkap">
                </td>
                <td>
                    <input type="email" name="users[${rowIndex}][email]" required
                        class="form-input py-2 text-sm" placeholder="email@contoh.com">
                </td>
                <td>
                    <input type="text" name="users[${rowIndex}][phone]"
                        class="form-input py-2 text-sm" placeholder="08xxx">
                </td>
                <td>
                    <button type="button" class="btn-icon text-red-500 hover:bg-red-50 remove-row" title="Hapus">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            rowIndex++;
            updateRowNumbers();
        });

        document.getElementById('userRows').addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const rows = document.querySelectorAll('.user-row');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                    updateRowNumbers();
                }
            }
        });

        function updateRowNumbers() {
            document.querySelectorAll('.user-row').forEach(function(row, i) {
                row.querySelector('.row-number').textContent = i + 1;
            });
        }

        document.getElementById('bulkForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Menyimpan...
            `;
        });
    </script>
    @endpush

</x-layouts.admin>
