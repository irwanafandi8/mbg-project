<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * List all admin (sekolah) users.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', UserRole::ADMIN->value)->with('school')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $admins = $query->paginate(10)->withQueryString();
        $schools = School::where('is_active', true)->orderBy('name')->get();
        $totalAdmins = User::where('role', UserRole::ADMIN->value)->count();

        return view('super-admin.admins.index', compact('admins', 'schools', 'totalAdmins'));
    }

    /**
     * Show create admin form.
     */
    public function create(): View
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('super-admin.admins.create', compact('schools'));
    }

    /**
     * Store a new admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'school_id' => ['required', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['role'] = UserRole::ADMIN->value;
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        AuditLog::log('create_user', User::class, $user->id, null, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
        ]);

        return redirect()->route('super_admin.admins.index')
            ->with('success', 'Admin sekolah berhasil ditambahkan.');
    }

    /**
     * Show edit admin form.
     */
    public function edit(User $user): View
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('super-admin.admins.edit', compact('user', 'schools'));
    }

    /**
     * Update an admin.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$user->id}"],
            'school_id' => ['required', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

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

        return redirect()->route('super_admin.admins.index')
            ->with('success', 'Admin sekolah berhasil diperbarui.');
    }

    /**
     * Toggle admin active status.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->getKey() === $request->user()->getAuthIdentifier()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status admin berhasil diperbarui.');
    }

    /**
     * Delete an admin.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->getKey() === $request->user()->getAuthIdentifier()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->complaints()->exists()) {
            return back()->with('error', 'Admin tidak dapat dihapus karena masih memiliki aduan.');
        }

        AuditLog::log('delete_user', User::class, $user->id, ['name' => $user->name], null);
        $user->delete();

        return redirect()->route('super_admin.admins.index')
            ->with('success', 'Admin sekolah berhasil dihapus.');
    }
}
