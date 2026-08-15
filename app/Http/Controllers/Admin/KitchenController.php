<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKitchenRequest;
use App\Models\AuditLog;
use App\Models\Kitchen;
use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    /**
     * List all kitchens.
     */
    public function index(Request $request): View
    {
        $query = Kitchen::withCount(['schools', 'complaints']);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('operational_status', $request->status);
        }

        $kitchens = $query->latest()->paginate(10)->withQueryString();

        $totalKitchens = Kitchen::count();
        $activeKitchens = Kitchen::where('operational_status', 'active')->count();
        $inactiveKitchens = Kitchen::where('operational_status', '!=', 'active')->count();

        return view('admin.kitchens.index', compact(
            'kitchens',
            'totalKitchens',
            'activeKitchens',
            'inactiveKitchens'
        ));
    }

    /**
     * Show create kitchen form.
     */
    public function create(): View
    {
        return view('admin.kitchens.create');
    }

    /**
     * Store a new kitchen.
     */
    public function store(StoreKitchenRequest $request): RedirectResponse
    {
        $kitchen = Kitchen::create($request->validated());
        AuditLog::log('create_kitchen', Kitchen::class, $kitchen->id, null, $kitchen->toArray());

        return redirect()->route(admin_route_name().'.kitchens.index')
            ->with('success', 'Dapur MBG berhasil ditambahkan.');
    }

    /**
     * Show a single kitchen.
     */
    public function show(Kitchen $kitchen): View
    {
        $kitchen->load(['schools', 'complaints.category']);
        $suggestions = Suggestion::with('user.school')
            ->whereHas('user.school', function ($query) use ($kitchen) {
                $query->where('kitchen_id', $kitchen->id);
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.kitchens.show', compact('kitchen', 'suggestions'));
    }

    /**
     * Show edit kitchen form.
     */
    public function edit(Kitchen $kitchen): View
    {
        return view('admin.kitchens.edit', compact('kitchen'));
    }

    /**
     * Update a kitchen.
     */
    public function update(Request $request, Kitchen $kitchen): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'person_in_charge' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'production_capacity' => ['required', 'integer', 'min:1'],
            'operational_status' => ['required', 'in:active,inactive,maintenance'],
        ]);

        $old = $kitchen->toArray();
        $kitchen->update($validated);
        AuditLog::log('update_kitchen', Kitchen::class, $kitchen->id, $old, $kitchen->fresh()->toArray());

        return redirect()->route(admin_route_name().'.kitchens.index')
            ->with('success', 'Dapur MBG berhasil diperbarui.');
    }

    /**
     * Delete a kitchen.
     */
    public function destroy(Kitchen $kitchen): RedirectResponse
    {
        if ($kitchen->schools()->exists()) {
            return back()->with('error', 'Dapur tidak dapat dihapus karena masih terhubung dengan sekolah.');
        }

        AuditLog::log('delete_kitchen', Kitchen::class, $kitchen->id, $kitchen->toArray(), null);
        $kitchen->delete();

        return redirect()->route(admin_route_name().'.kitchens.index')
            ->with('success', 'Dapur MBG berhasil dihapus.');
    }
}
