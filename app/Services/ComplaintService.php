<?php

namespace App\Services;

use App\Enums\ComplaintStatus;
use App\Models\AuditLog;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintService
{
    /**
     * Create a new complaint with attachments.
     */
    public function create(array $data, array $files = []): Complaint
    {
        return DB::transaction(function () use ($data, $files) {
            $complaint = Complaint::create($data);

            if (!empty($files)) {
                $this->storeAttachments($complaint, $files);
            }

            AuditLog::log(
                'create_complaint',
                Complaint::class,
                $complaint->id,
                null,
                $complaint->toArray()
            );

            return $complaint;
        });
    }

    /**
     * Update complaint status.
     */
    public function updateStatus(Complaint $complaint, ComplaintStatus $status, ?string $responseMessage = null): Complaint
    {
        return DB::transaction(function () use ($complaint, $status, $responseMessage) {
            $oldValues = $complaint->only(['status', 'resolved_at']);

            $complaint->status = $status;

            if ($status === ComplaintStatus::RESOLVED) {
                $complaint->resolved_at = now();
            }

            $complaint->save();

            if ($responseMessage) {
                $complaint->responses()->create([
                    'user_id' => auth()->id(),
                    'message' => $responseMessage,
                ]);
            }

            AuditLog::log(
                'update_complaint_status',
                Complaint::class,
                $complaint->id,
                $oldValues,
                $complaint->only(['status', 'resolved_at'])
            );

            return $complaint;
        });
    }

    /**
     * Update a complaint.
     */
    public function update(Complaint $complaint, array $data, array $files = []): Complaint
    {
        return DB::transaction(function () use ($complaint, $data, $files) {
            $oldValues = $complaint->toArray();

            $complaint->update($data);

            if (!empty($files)) {
                $this->storeAttachments($complaint, $files);
            }

            AuditLog::log(
                'update_complaint',
                Complaint::class,
                $complaint->id,
                $oldValues,
                $complaint->fresh()->toArray()
            );

            return $complaint;
        });
    }

    /**
     * Delete a complaint and its attachments.
     */
    public function delete(Complaint $complaint): bool
    {
        return DB::transaction(function () use ($complaint) {
            // Delete physical files
            foreach ($complaint->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            AuditLog::log(
                'delete_complaint',
                Complaint::class,
                $complaint->id,
                $complaint->toArray(),
                null
            );

            return $complaint->delete();
        });
    }

    /**
     * Store attachments for a complaint.
     */
    private function storeAttachments(Complaint $complaint, array $files): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store('complaints/' . $complaint->id, 'public');

                ComplaintAttachment::create([
                    'complaint_id' => $complaint->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
    }
}
