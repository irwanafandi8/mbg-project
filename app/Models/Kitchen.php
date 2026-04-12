<?php

namespace App\Models;

use App\Enums\OperationalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kitchen extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'person_in_charge',
        'address',
        'phone',
        'production_capacity',
        'operational_status',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'operational_status' => OperationalStatus::class,
            'production_capacity' => 'integer',
        ];
    }

    /**
     * Get schools mapped to this kitchen.
     */
    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }

    /**
     * Get complaints related to this kitchen.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get the total number of students served.
     */
    public function getTotalStudentsAttribute(): int
    {
        return $this->schools->count();
    }
}
