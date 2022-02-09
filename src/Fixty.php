<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

final class Fixty
{
    public static function firstname(bool $rand = false): string
    {
        return 'Dave';
    }

    public static function surname(bool $rand = false): string
    {
        return 'Lister';
    }

    public static function bankAccountNumber(): string
    {
        return '12345811/0600';
    }

    public static function bankName(): string
    {
        return 'Equa';
    }
}
