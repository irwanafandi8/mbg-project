<?php

namespace App\Models;

use App\Enums\ComplaintPriority;
use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'complaint_number',
        'user_id',
        'kitchen_id',
        'category_id',
        'title',
        'description',
        'status',
        'priority',
        'resolved_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'status' => ComplaintStatus::class,
            'priority' => ComplaintPriority::class,
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Complaint $complaint) {
            if (empty($complaint->complaint_number)) {
                $complaint->complaint_number = self::generateComplaintNumber();
            }
        });
    }

    /**
     * Generate a unique complaint number.
     */
    public static function generateComplaintNumber(): string
    {
        $prefix = 'AD-';
        $date = now()->format('Ymd');
        $lastComplaint = self::where('complaint_number', 'like', $prefix . $date . '%')
            ->orderBy('complaint_number', 'desc')
            ->first();

        if ($lastComplaint) {
            $lastNumber = (int) substr($lastComplaint->complaint_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the user who created this complaint.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kitchen related to this complaint.
     */
    public function kitchen(): BelongsTo
    {
        return $this->belongsTo(Kitchen::class);
    }

    /**
     * Get the category of this complaint.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    /**
     * Get attachments for this complaint.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    /**
     * Get responses for this complaint.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(ComplaintResponse::class);
    }

    /**
     * Scope complaints by status.
     */
    public function scopeByStatus($query, ComplaintStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pending complaints.
     */
    public function scopePending($query)
    {
        return $query->where('status', ComplaintStatus::PENDING);
    }

    /**
     * Scope resolved complaints.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', ComplaintStatus::RESOLVED);
    }

    /**
     * Scope unresolved complaints (not resolved or rejected).
     */
    public function scopeUnresolved($query)
    {
        return $query->whereNotIn('status', [
            ComplaintStatus::RESOLVED,
            ComplaintStatus::REJECTED,
        ]);
    }
}
