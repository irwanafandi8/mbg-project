<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreComplaintRequest;
use App\Http\Requests\Admin\UpdateComplaintStatusRequest;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Services\ComplaintService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function __construct(private readonly ComplaintService $complaintService) {}

    /**
     * List all complaints.
     */
    public function index(Request $request): View
    {
        $query = Complaint::with(['user.school', 'kitchen', 'category'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kitchen_id')) {
            $query->where('kitchen_id', $request->kitchen_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('complaint_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $complaints = $query->paginate(10)->withQueryString();

        $totalComplaints  = Complaint::count();
        $pendingComplaints = Complaint::where('status', ComplaintStatus::PENDING)->count();
        $resolvedComplaints = Complaint::where('status', ComplaintStatus::RESOLVED)->count();

        $kitchens   = \App\Models\Kitchen::orderBy('name')->get();
        $categories = \App\Models\ComplaintCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.complaints.index', compact(
            'complaints',
            'totalComplaints',
            'pendingComplaints',
            'resolvedComplaints',
            'kitchens',
            'categories'
        ));
    }

    /**
     * Show create complaint form (admin sekolah).
     */
    public function create(): View
    {
        $user   = auth()->user();
        $school = $user->school;

        abort_if(!$school || !$school->kitchen_id, 403,
            'Sekolah Anda belum terhubung dengan dapur MBG. Hubungi administrator.');

        $categories = ComplaintCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.complaints.create', compact('categories', 'school'));
    }

    /**
     * Store a new complaint (admin sekolah).
     */
    public function store(StoreComplaintRequest $request): RedirectResponse
    {
        $user   = auth()->user();
        $school = $user->school;

        $data = array_merge($request->validated(), [
            'user_id'    => $user->id,
            'kitchen_id' => $school->kitchen_id,
        ]);

        unset($data['attachments']);

        $complaint = $this->complaintService->create(
            $data,
            $request->file('attachments', [])
        );

        return redirect()->route(admin_route_name() . '.complaints.show', $complaint)
            ->with('success', 'Aduan berhasil dikirim. Nomor aduan Anda: ' . $complaint->complaint_number);
    }

    /**
     * Show a single complaint.
     */
    public function show(Complaint $complaint): View
    {
        $complaint->load(['user.school', 'kitchen', 'category', 'attachments', 'responses.user']);
        $statuses = ComplaintStatus::cases();

        return view('admin.complaints.show', compact('complaint', 'statuses'));
    }

    /**
     * Update complaint status.
     */
    public function updateStatus(UpdateComplaintStatusRequest $request, Complaint $complaint): RedirectResponse
    {
        $status = ComplaintStatus::from($request->status);
        $this->complaintService->updateStatus($complaint, $status, $request->response);

        return redirect()->route(admin_route_name() . '.complaints.show', $complaint)
            ->with('success', 'Status aduan berhasil diperbarui.');
    }
}
