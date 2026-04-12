<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Show audit logs.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('action', 'like', "%{$request->search}%");
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(10)->withQueryString();

        return view('admin.audit-logs.index', compact('logs'));
    }
}
