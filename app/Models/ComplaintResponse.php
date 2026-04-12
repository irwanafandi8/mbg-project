<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'complaint_id',
        'user_id',
        'message',
    ];

    /**
     * Get the complaint this response belongs to.
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Get the user who created this response.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
