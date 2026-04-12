<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get complaints in this category.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'category_id');
    }

    /**
     * Get the complaint count attribute.
     */
    public function getComplaintCountAttribute(): int
    {
        return $this->complaints()->count();
    }
}
