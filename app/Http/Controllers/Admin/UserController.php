<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    /**
     * List users - school admin only sees users in their school.
     */
    public function index(Request $request): View
    {
        $query = User::with('school')->latest();

        if (auth()->user()->isSchoolAdmin()) {
            $schoolId = auth()->user()->school_id;
            $query->where('school_id', $schoolId)->where('role', UserRole::USER->value);
        }

        if ($request->filled('role')) {
            if (auth()->user()->isSchoolAdmin()) {
                $query->where('role', UserRole::USER->value);
            } else {
                $query->where('role', $request->role);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        if (auth()->user()->isSchoolAdmin()) {
            $schoolId = auth()->user()->school_id;
            $totalSchoolUsers = User::where('school_id', $schoolId)->where('role', UserRole::USER->value)->count();
            $schools = collect();
        } else {
            $schools = School::where('is_active', true)->orderBy('name')->get();
            $totalSchoolUsers = User::where('role', UserRole::USER->value)->count();
        }
        $totalAdmins = User::where('role', UserRole::ADMIN->value)->count();

        return view('admin.users.index', compact('users', 'schools', 'totalAdmins', 'totalSchoolUsers'));
    }

    /**
     * Show create user form.
     */
    public function create(): View
    {
        if (auth()->user()->isSchoolAdmin()) {
            $school = auth()->user()->school;

            return view('admin.users.create', compact('school'));
        }

        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.create', compact('schools'));
    }

    /**
     * Show create super admin form.
     */
    public function createAdmin(): View
    {
        return view('admin.users.create-admin');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,user'],
            'school_id' => ['required_if:role,user', 'nullable', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        if (auth()->user()->isSchoolAdmin()) {
            $validated['role'] = UserRole::USER;
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($validated['role'] === UserRole::ADMIN->value) {
            $validated['school_id'] = null;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        AuditLog::log('create_user', User::class, $user->id, null, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
        ]);

        return redirect()->route(admin_route_name().'.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show bulk create user form.
     */
    public function bulkCreate(): View
    {
        if (auth()->user()->isSchoolAdmin()) {
            $school = auth()->user()->school;

            return view('admin.users.bulk-create', compact('school'));
        }

        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.bulk-create', compact('schools'));
    }

    /**
     * Download CSV template for bulk user import.
     */
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'template_tambah_pengguna.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'phone']);
            fputcsv($file, ['Andi Saputra', 'andi@contoh.com', '081234567890']);
            fputcsv($file, ['Siti Rahmawati', 'siti@contoh.com', '081298765432']);
            fputcsv($file, ['Budi Santoso', 'budi@contoh.com', '081298765433']);
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Parse uploaded CSV/Excel file and return data for bulk create form.
     */
    public function bulkUpload(Request $request): View
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:5120'],
        ]);

        $file = $request->file('csv_file');
        $rows = [];
        $errors = [];

        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['xlsx', 'xls'])) {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            if (count($data) < 2) {
                $errors[] = 'File Excel kosong atau tidak memiliki data.';
            } else {
                $header = array_map('strtolower', array_map('trim', $data[0]));
                $requiredCols = ['name', 'email'];
                $missingCols = array_diff($requiredCols, $header);

                if (! empty($missingCols)) {
                    $errors[] = 'Kolom yang wajib ada: '.implode(', ', $requiredCols).'. Kolom tidak ditemukan: '.implode(', ', $missingCols).'.';
                } else {
                    $emailIndex = array_search('email', $header);
                    $nameIndex = array_search('name', $header);
                    $phoneIndex = array_search('phone', $header);

                    for ($i = 1; $i < count($data); $i++) {
                        $lineNum = $i + 1;
                        $name = trim((string) ($data[$i][$nameIndex] ?? ''));
                        $email = trim((string) ($data[$i][$emailIndex] ?? ''));
                        $phone = $phoneIndex !== false ? trim((string) ($data[$i][$phoneIndex] ?? '')) : '';

                        if ($name === '' && $email === '') {
                            continue;
                        }

                        if ($name === '') {
                            $errors[] = "Baris {$lineNum}: Nama wajib diisi.";

                            continue;
                        }

                        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Baris {$lineNum}: Email tidak valid ({$email}).";

                            continue;
                        }

                        $rows[] = [
                            'name' => $name,
                            'email' => $email,
                            'phone' => $phone,
                        ];
                    }
                }
            }
        } else {
            if (($handle = fopen($file->getPathname(), 'r')) !== false) {
                $header = fgetcsv($handle, 0, ',');

                if ($header === false) {
                    $errors[] = 'File CSV kosong atau format tidak valid.';
                } else {
                    $header = array_map('strtolower', array_map('trim', $header));

                    $requiredCols = ['name', 'email'];
                    $missingCols = array_diff($requiredCols, $header);

                    if (! empty($missingCols)) {
                        $errors[] = 'Kolom yang wajib ada: '.implode(', ', $requiredCols).'. Kolom tidak ditemukan: '.implode(', ', $missingCols).'.';
                    } else {
                        $emailIndex = array_search('email', $header);
                        $nameIndex = array_search('name', $header);
                        $phoneIndex = array_search('phone', $header);

                        $lineNum = 1;
                        while (($data = fgetcsv($handle, 0, ',')) !== false) {
                            $lineNum++;
                            $name = trim($data[$nameIndex] ?? '');
                            $email = trim($data[$emailIndex] ?? '');
                            $phone = $phoneIndex !== false ? trim($data[$phoneIndex] ?? '') : '';

                            if ($name === '' && $email === '') {
                                continue;
                            }

                            if ($name === '') {
                                $errors[] = "Baris {$lineNum}: Nama wajib diisi.";

                                continue;
                            }

                            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = "Baris {$lineNum}: Email tidak valid ({$email}).";

                                continue;
                            }

                            $rows[] = [
                                'name' => $name,
                                'email' => $email,
                                'phone' => $phone,
                            ];
                        }
                    }
                }
                fclose($handle);
            }
        }

        if (auth()->user()->isSchoolAdmin()) {
            $school = auth()->user()->school;

            return view('admin.users.bulk-create', compact('school', 'rows', 'errors'));
        }

        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.bulk-create', compact('schools', 'rows', 'errors'));
    }

    /**
     * Store multiple users at once.
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'users' => ['required', 'array', 'min:1'],
            'users.*.name' => ['required', 'string', 'max:255'],
            'users.*.email' => ['required', 'email', 'unique:users,email'],
            'users.*.phone' => ['nullable', 'string', 'max:20'],
            'default_password' => ['nullable', 'string', 'min:8'],
        ]);

        $password = $validated['default_password'] ?? 'password123';
        $successCount = 0;
        $errorRows = [];

        DB::beginTransaction();
        try {
            foreach ($validated['users'] as $index => $userData) {
                $userData['password'] = Hash::make($password);
                $userData['role'] = UserRole::USER;
                $userData['school_id'] = auth()->user()->school_id;
                $userData['is_active'] = true;

                $user = User::create($userData);
                AuditLog::log('create_user', User::class, $user->id, null, [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->value,
                ]);
                $successCount++;
            }
            DB::commit();

            return redirect()->route(admin_route_name().'.users.index')
                ->with('success', "{$successCount} pengguna berhasil ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menambahkan pengguna: '.$e->getMessage());
        }
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        if (auth()->user()->isSchoolAdmin()) {
            abort_if($user->school_id !== auth()->user()->school_id || $user->role !== UserRole::USER, 403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$user->id}"],
            'role' => ['required', 'in:admin,user'],
            'school_id' => ['required_if:role,user', 'nullable', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        if (auth()->user()->isSchoolAdmin()) {
            $validated['role'] = UserRole::USER;
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($validated['role'] === UserRole::ADMIN->value) {
            $validated['school_id'] = null;
        }

        if (! isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        AuditLog::log('update_user', User::class, $user->id, null, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
        ]);

        return redirect()->route(admin_route_name().'.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user): View
    {
        if (auth()->user()->isSchoolAdmin()) {
            abort_if($user->school_id !== auth()->user()->school_id || $user->role !== UserRole::USER, 403);
            $school = auth()->user()->school;

            return view('admin.users.edit', compact('user', 'school'));
        }

        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'schools'));
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if (auth()->user()->isSchoolAdmin()) {
            abort_if($user->school_id !== auth()->user()->school_id || $user->role !== UserRole::USER, 403);
        }

        if ($user->getKey() === $request->user()->getAuthIdentifier()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    /**
     * Delete a user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if (auth()->user()->isSchoolAdmin()) {
            abort_if($user->school_id !== auth()->user()->school_id || $user->role !== UserRole::USER, 403);
        }

        if ($user->getKey() === $request->user()->getAuthIdentifier()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->complaints()->exists()) {
            return back()->with('error', 'Pengguna tidak dapat dihapus karena masih memiliki aduan.');
        }

        AuditLog::log('delete_user', User::class, $user->id, ['name' => $user->name], null);
        $user->delete();

        return redirect()->route(admin_route_name().'.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
