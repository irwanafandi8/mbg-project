<x-layouts.admin title="Profil" breadcrumb="Kelola informasi akun Anda">

    <div class="max-w-4xl mx-auto space-y-6">

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Informasi Profil</h3>
            </div>
            <div class="card-body">
                @php
                    $profileUpdateRoute = auth()->user()->isSuperAdmin() ? 'super_admin.profile.update' : 'admin.profile.update';
                    $passwordUpdateRoute = auth()->user()->isSuperAdmin() ? 'super_admin.profile.password' : 'admin.profile.password';
                @endphp
                <form action="{{ route($profileUpdateRoute) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label for="name" class="form-label block mb-1">Nama</label>
                        <input type="text" name="name" id="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                            placeholder="Masukkan nama" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label block mb-1">Email</label>
                        <input type="email" name="email" id="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                            placeholder="contoh@email.com" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="form-label block mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                            placeholder="Contoh: 081234567890" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="btn-primary">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-slate-800">Ubah Password</h3>
            </div>
            <div class="card-body">
                <form action="{{ route($passwordUpdateRoute) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label for="current_password" class="form-label block mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="form-input {{ $errors->has('current_password') ? 'error' : '' }}"
                            placeholder="Masukkan password saat ini" required>
                        @error('current_password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label block mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="Masukkan password baru" required>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label block mb-1">Konfirmasi Password
                            Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-input" placeholder="Ulangi password baru" required>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="btn-success">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.admin>
