<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSuggestionRequest;
use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    /**
     * List all suggestions.
     */
    public function index(Request $request): View
    {
        $query = Suggestion::with('user.school')->latest();

        if ($request->filled('status')) {
            $isRead = $request->status === 'read';
            $query->where('is_read', $isRead);
        }

        $suggestions = $query->paginate(10)->withQueryString();
        $totalCount = Suggestion::count();
        $readCount = Suggestion::where('is_read', true)->count();
        $unreadCount = Suggestion::unread()->count();

        return view('admin.suggestions.index', compact('suggestions', 'unreadCount', 'readCount', 'totalCount'));
    }

    /**
     * Show create suggestion form (admin sekolah).
     */
    public function create(): View
    {
        return view('admin.suggestions.create');
    }

    /**
     * Store a new suggestion (admin sekolah).
     */
    public function store(StoreSuggestionRequest $request): RedirectResponse
    {
        $request->user()->suggestions()->create($request->validated());

        return redirect()->route(admin_route_name() . '.suggestions.index')
            ->with('success', 'Saran berhasil dikirim. Terima kasih atas masukan Anda!');
    }

    /**
     * Mark suggestion as read.
     */
    public function markRead(Suggestion $suggestion): RedirectResponse
    {
        $suggestion->update(['is_read' => true]);
        return back()->with('success', 'Saran telah ditandai sebagai dibaca.');
    }

    /**
     * Mark all suggestions as read.
     */
    public function markAllRead(): RedirectResponse
    {
        Suggestion::unread()->update(['is_read' => true]);
        return back()->with('success', 'Semua saran telah ditandai sebagai dibaca.');
    }
}
