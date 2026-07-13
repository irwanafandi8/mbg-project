<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrator',
            self::ADMIN => 'Administrator Sekolah',
            self::USER => 'Siswa / Orang Tua',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'super_admin.dashboard',
            self::ADMIN => 'admin.dashboard',
            self::USER => 'user.dashboard',
        };
    }
}
