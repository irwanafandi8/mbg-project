<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolRequest;
use App\Models\AuditLog;
use App\Models\Kitchen;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    /**
     * List all schools.
     */
    public function index(Request $request): View
    {
        $query = School::with('kitchen')->withCount('users');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('npsn', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('kitchen_id')) {
            $query->where('kitchen_id', $request->kitchen_id);
        }

        $schools   = $query->latest()->paginate(10)->withQueryString();
        $kitchens  = Kitchen::orderBy('name')->get();
        $totalSchools = School::count();
        $mappedSchools = School::whereNotNull('kitchen_id')->count();
        $unmappedSchools = School::whereNull('kitchen_id')->count();

        return view('admin.schools.index', compact(
            'schools',
            'kitchens',
            'totalSchools',
            'mappedSchools',
            'unmappedSchools'
        ));
    }

    /**
     * Show create school form.
     */
    public function create(): View
    {
        $kitchens = Kitchen::orderBy('name')->get();

        return view('admin.schools.create', compact('kitchens'));
    }

    /**
     * Store a new school.
     */
    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $school = School::create($request->validated());
        AuditLog::log('create_school', School::class, $school->id, null, $school->toArray());

        return redirect()->route('admin.schools.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    /**
     * Update a school.
     */
    public function update(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'npsn'       => ['required', 'string', 'max:20', "unique:schools,npsn,{$school->id}"],
            'address'    => ['required', 'string'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'kitchen_id' => ['nullable', 'exists:kitchens,id'],
            'is_active'  => ['boolean'],
        ]);

        $old = $school->toArray();
        $school->update($validated);
        AuditLog::log('update_school', School::class, $school->id, $old, $school->fresh()->toArray());

        return redirect()->route('admin.schools.index')
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    /**
     * Show edit school form.
     */
    public function edit(School $school): View
    {
        $kitchens = Kitchen::orderBy('name')->get();

        return view('admin.schools.edit', compact('school', 'kitchens'));
    }

    /**
     * Delete a school.
     */
    public function destroy(School $school): RedirectResponse
    {
        if ($school->users()->exists()) {
            return back()->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki akun pengguna.');
        }

        AuditLog::log('delete_school', School::class, $school->id, $school->toArray(), null);
        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }
}
