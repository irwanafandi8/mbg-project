<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case PENDING = 'pending';
    case RECEIVED = 'received';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu',
            self::RECEIVED => 'Diterima',
            self::IN_PROGRESS => 'Sedang Diproses',
            self::RESOLVED => 'Selesai',
            self::REJECTED => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::RECEIVED => 'blue',
            self::IN_PROGRESS => 'orange',
            self::RESOLVED => 'green',
            self::REJECTED => 'red',
        };
    }

    public function bgClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::RECEIVED => 'bg-blue-100 text-blue-800',
            self::IN_PROGRESS => 'bg-orange-100 text-orange-800',
            self::RESOLVED => 'bg-green-100 text-green-800',
            self::REJECTED => 'bg-red-100 text-red-800',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-500',
            self::RECEIVED => 'bg-blue-500',
            self::IN_PROGRESS => 'bg-orange-500',
            self::RESOLVED => 'bg-green-500',
            self::REJECTED => 'bg-red-500',
        };
    }
}
