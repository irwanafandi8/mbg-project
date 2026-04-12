<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreSuggestionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    /**
     * List user's suggestions.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $suggestions = $user->suggestions()->latest()->paginate(10);
        $readCount = $user->suggestions()->where('is_read', true)->count();
        $unreadCount = $user->suggestions()->where('is_read', false)->count();

        return view('user.suggestions.index', compact('suggestions', 'readCount', 'unreadCount'));
    }

    /**
     * Show create suggestion form.
     */
    public function create(): View
    {
        return view('user.suggestions.create');
    }

    /**
     * Store a new suggestion.
     */
    public function store(StoreSuggestionRequest $request): RedirectResponse
    {
        $request->user()->suggestions()->create($request->validated());

        return redirect()->route('user.suggestions.index')
            ->with('success', 'Saran berhasil dikirim. Terima kasih atas masukan Anda!');
    }
}
