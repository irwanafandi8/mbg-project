<?php

namespace App\Enums;

enum ComplaintPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Rendah',
            self::MEDIUM => 'Sedang',
            self::HIGH => 'Tinggi',
        };
    }

    public function bgClass(): string
    {
        return match ($this) {
            self::LOW => 'bg-slate-100 text-slate-700',
            self::MEDIUM => 'bg-amber-100 text-amber-700',
            self::HIGH => 'bg-red-100 text-red-700',
        };
    }
}
