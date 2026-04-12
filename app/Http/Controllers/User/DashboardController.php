<?php

namespace App\Http\Controllers\User;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show user dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $school = $user->school;

        $complaints = $user->complaints()->latest()->limit(3)->get();

        $totalComplaints    = $user->complaints()->count();
        $pendingComplaints  = $user->complaints()->where('status', ComplaintStatus::PENDING)->count();
        $processedComplaints = $user->complaints()->whereIn('status', [
            ComplaintStatus::RECEIVED,
            ComplaintStatus::IN_PROGRESS,
        ])->count();
        $resolvedComplaints = $user->complaints()->where('status', ComplaintStatus::RESOLVED)->count();

        // Suggestions stats
        $totalSuggestions = $user->suggestions()->count();
        $readSuggestions = $user->suggestions()->where('is_read', true)->count();
        $unreadSuggestions = $totalSuggestions - $readSuggestions;

        return view('user.dashboard', compact(
            'user',
            'school',
            'complaints',
            'totalComplaints',
            'pendingComplaints',
            'processedComplaints',
            'resolvedComplaints',
            'totalSuggestions',
            'readSuggestions',
            'unreadSuggestions'
        ));
    }
}
