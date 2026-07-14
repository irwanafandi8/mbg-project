<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * List all users.
     */
    public function index(Request $request): View
    {
        $query = User::with('school')->latest();

        // School admin can only see user (siswa/orang tua)
        if (auth()->user()->isSchoolAdmin()) {
            $query->where('role', UserRole::USER->value);
        }

        if ($request->filled('role')) {
            // School admin cannot filter by admin role
            if (auth()->user()->isSchoolAdmin() && $request->role === 'admin') {
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

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $users = $query->paginate(10)->withQueryString();
        $schools = School::where('is_active', true)->orderBy('name')->get();
        $totalAdmins = User::where('role', UserRole::ADMIN->value)->count();
        $totalSchoolUsers = User::where('role', UserRole::USER->value)->count();

        return view('admin.users.index', compact('users', 'schools', 'totalAdmins', 'totalSchoolUsers'));
    }

    /**
     * Show create school administrator form.
     */
    public function create(): View
    {
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

        return redirect()->route(admin_route_name() . '.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
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

        if ($validated['role'] === UserRole::ADMIN->value) {
            $validated['school_id'] = null;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        if (!empty($validated['password'])) {
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

        return redirect()->route(admin_route_name() . '.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user): View
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'schools'));
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->getKey() === $request->user()->getAuthIdentifier()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    /**
     * Delete a user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->getKey() === $request->user()->getAuthIdentifier()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->complaints()->exists()) {
            return back()->with('error', 'Pengguna tidak dapat dihapus karena masih memiliki aduan.');
        }

        AuditLog::log('delete_user', User::class, $user->id, ['name' => $user->name], null);
        $user->delete();

        return redirect()->route(admin_route_name() . '.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
