<?php

namespace App\Support;

/**
 * Places in the Municipality of Abuyog, Leyte (63 barangays, 10 of them poblacion).
 */
class Abuyog
{
    public const MUNICIPALITY = 'Abuyog';
    public const PROVINCE     = 'Leyte';
    public const FULL         = 'Abuyog, Leyte';

    public const POBLACION = [
        'Bito', 'Buntay', 'Can-uguib', 'Guintagbucan', 'Loyonsawang',
        'Nalibunan', 'Santa Fe', 'Santa Lucia', 'Santo Niño', 'Victory',
    ];

    public const OTHERS = [
        'Alangilan', 'Anibongan', 'Bagacay', 'Bahay', 'Balinsasayao', 'Balocawe',
        'Balocawehay', 'Barayong', 'Bayabas', 'Buaya', 'Buenavista', 'Bulak', 'Bunga',
        'Burubud-an', 'Cadac-an', 'Cagbolo', 'Can-aporong', 'Canmarating', 'Capilian',
        'Combis', 'Dingle', 'Hampipila', 'Katipunan', 'Kikilo', 'Laray', 'Lawa-an',
        'Libertad', 'Mag-atubang', 'Mahagna (New Cagbolo)', 'Mahayahay', 'Maitum',
        'Malaguicay', 'Matagnao', 'Nebga', 'New Taligue', 'Odiongan', 'Old Taligue',
        'Pagsang-an', 'Paguite', 'Parasanon', 'Picas Sur', 'Pilar', 'Pinamanagan',
        'Salvacion', 'San Francisco', 'San Isidro', 'San Roque', 'Tabigue', 'Tadoc',
        'Tib-o', 'Tinalian', 'Tinocolan', 'Tuy-a',
    ];

    /** All 63 barangays, A-Z. */
    public static function all(): array
    {
        $all = array_merge(self::POBLACION, self::OTHERS);
        sort($all, SORT_NATURAL | SORT_FLAG_CASE);

        return $all;
    }

    /** For <optgroup> dropdowns. */
    public static function grouped(): array
    {
        $poblacion = self::POBLACION;
        $others = self::OTHERS;
        sort($poblacion, SORT_NATURAL | SORT_FLAG_CASE);
        sort($others, SORT_NATURAL | SORT_FLAG_CASE);

        return [
            'Poblacion barangays' => $poblacion,
            'Other barangays'     => $others,
        ];
    }

    /** "Purok 3, Brgy. Bito, Abuyog, Leyte" */
    public static function location(?string $address, ?string $barangay): string
    {
        return collect([
            filled($address) ? $address : null,
            filled($barangay) ? 'Brgy. '.$barangay : null,
            self::FULL,
        ])->filter()->implode(', ');
    }
}
