<?php

namespace App\Enums;

enum OperationalStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case MAINTENANCE = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Buka',
            self::INACTIVE => 'Tutup',
            self::MAINTENANCE => 'Pemeliharaan',
        };
    }

    public function bgClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-green-100 text-green-700',
            self::INACTIVE => 'bg-red-100 text-red-700',
            self::MAINTENANCE => 'bg-amber-100 text-amber-700',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-green-500',
            self::INACTIVE => 'bg-red-500',
            self::MAINTENANCE => 'bg-amber-500',
        };
    }
}
