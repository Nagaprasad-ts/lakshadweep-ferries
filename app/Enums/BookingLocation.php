<?php

namespace App\Enums;

enum BookingLocation: string
{
    case Agatti = 'Agatti Island - Lakshadweep';
    case Bangaram = 'Bangaram Island - Lakshadweep';
    case Kadmat = 'Kadmat Island - Lakshadweep';
    case Minicoy = 'Minicoy Island - Lakshadweep';


    public static function options(): array
{
    return collect(self::cases())
        ->mapWithKeys(fn ($case) => [$case->value => $case->value])
        ->toArray();
}
}