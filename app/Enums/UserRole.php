<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'Sekolah',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::ADMIN => 'admin.dashboard',
            self::USER => 'user.dashboard',
        };
    }
}
