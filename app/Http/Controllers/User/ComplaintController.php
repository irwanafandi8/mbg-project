<?php

namespace App\Http\Controllers\User;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreComplaintRequest;
use App\Http\Requests\User\UpdateComplaintRequest;
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
     * List user's complaints.
     */
    public function index(Request $request): View
    {
        $query = auth()->user()->complaints()
            ->with(['kitchen', 'category'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(10)->withQueryString();
        $statuses   = ComplaintStatus::cases();

        return view('user.complaints.index', compact('complaints', 'statuses'));
    }

    /**
     * Show create complaint form.
     */
    public function create(): View
    {
        $user   = auth()->user();
        $school = $user->school;

        abort_if(!$school || !$school->kitchen_id, 403,
            'Sekolah Anda belum terhubung dengan dapur MBG. Hubungi administrator.');

        $categories = ComplaintCategory::where('is_active', true)->orderBy('name')->get();

        return view('user.complaints.create', compact('categories', 'school'));
    }

    /**
     * Store a new complaint.
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

        return redirect()->route('user.complaints.show', $complaint)
            ->with('success', 'Aduan berhasil dikirim. Nomor aduan Anda: ' . $complaint->complaint_number);
    }

    /**
     * Show a complaint detail.
     */
    public function show(Complaint $complaint): View
    {
        abort_if($complaint->user_id !== auth()->id(), 403);
        $complaint->load(['kitchen', 'category', 'attachments', 'responses.user']);

        return view('user.complaints.show', compact('complaint'));
    }

    /**
     * Show edit complaint form.
     */
    public function edit(Complaint $complaint): View
    {
        abort_if($complaint->user_id !== auth()->id(), 403);
        abort_if($complaint->status !== ComplaintStatus::PENDING, 403,
            'Aduan hanya dapat diedit ketika statusnya masih Menunggu.');

        $categories = ComplaintCategory::where('is_active', true)->orderBy('name')->get();
        $school     = auth()->user()->school;

        return view('user.complaints.edit', compact('complaint', 'categories', 'school'));
    }

    /**
     * Update a complaint.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validated();
        unset($data['attachments']);

        $this->complaintService->update(
            $complaint,
            $data,
            $request->file('attachments', [])
        );

        return redirect()->route('user.complaints.show', $complaint)
            ->with('success', 'Aduan berhasil diperbarui.');
    }

    /**
     * Delete a complaint (only pending).
     */
    public function destroy(Complaint $complaint): RedirectResponse
    {
        abort_if($complaint->user_id !== auth()->id(), 403);
        abort_if($complaint->status !== ComplaintStatus::PENDING, 403,
            'Aduan hanya dapat dihapus ketika statusnya masih Menunggu.');

        $this->complaintService->delete($complaint);

        return redirect()->route('user.complaints.index')
            ->with('success', 'Aduan berhasil dihapus.');
    }
}
