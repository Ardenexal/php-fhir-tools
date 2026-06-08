<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Ucum;

/**
 * Minimal UCUM unit conversion shared across components.
 *
 * Converts a recognised UCUM code to a canonical base unit so two quantities of the same
 * physical dimension can be compared. This is intentionally a small, hand-maintained table —
 * NOT a full UCUM engine — covering the units the test corpus and FHIRPath comparisons exercise
 * (mass, length, volume, and the day/week duration pair). Unknown codes return null, which callers
 * treat as "not commensurable / cannot compare".
 *
 * Calendar-duration keyword semantics (year/month approximations, the FHIRPath month↔year
 * incomparability rule) deliberately live in the FHIRPath ComparisonService, not here: those are
 * FHIRPath temporal rules, not UCUM unit conversion.
 */
final class UcumConverter
{
    public const string SYSTEM_URL = 'http://unitsofmeasure.org';

    /**
     * UCUM code => canonical base unit + multiplicative factor to that base.
     */
    private const array CONVERSIONS = [
        '1'       => ['base' => '1',  'factor' => 1.0],
        'kg'      => ['base' => 'kg', 'factor' => 1.0],
        'g'       => ['base' => 'kg', 'factor' => 0.001],
        'mg'      => ['base' => 'kg', 'factor' => 0.000001],
        '[lb_av]' => ['base' => 'kg', 'factor' => 0.45359237],
        'm'       => ['base' => 'm',  'factor' => 1.0],
        'cm'      => ['base' => 'm',  'factor' => 0.01],
        'mm'      => ['base' => 'm',  'factor' => 0.001],
        'km'      => ['base' => 'm',  'factor' => 1000.0],
        '[in_i]'  => ['base' => 'm',  'factor' => 0.0254],
        '[ft_i]'  => ['base' => 'm',  'factor' => 0.3048],
        '[mi_i]'  => ['base' => 'm',  'factor' => 1609.344],
        'L'       => ['base' => 'L',  'factor' => 1.0],
        'mL'      => ['base' => 'L',  'factor' => 0.001],
        'wk'      => ['base' => 'd',  'factor' => 7.0],
        'd'       => ['base' => 'd',  'factor' => 1.0],
    ];

    /**
     * Convert a value in the given UCUM code to its canonical base unit.
     *
     * Returns null when the code is not in the conversion table.
     *
     * @return array{base: string, value: float}|null
     */
    public function toBase(string $code, float $value): ?array
    {
        $definition = self::CONVERSIONS[$code] ?? null;
        if ($definition === null) {
            return null;
        }

        return [
            'base'  => $definition['base'],
            'value' => $value * $definition['factor'],
        ];
    }

    /**
     * Whether the given UCUM code is one this converter can convert (i.e. present in the table).
     *
     * Lets callers distinguish "unit not recognised by this minimal converter" from "units are of
     * different physical dimensions" when {@see compare()} returns null — the two are not the same
     * diagnosis, even though both block a comparison.
     */
    public function knows(string $code): bool
    {
        return isset(self::CONVERSIONS[$code]);
    }

    /**
     * Whether two UCUM codes belong to the same physical dimension (both recognised and sharing a
     * canonical base unit), i.e. their quantities can be ordered against one another.
     */
    public function areCommensurable(string $codeA, string $codeB): bool
    {
        $a = self::CONVERSIONS[$codeA] ?? null;
        $b = self::CONVERSIONS[$codeB] ?? null;

        return $a !== null && $b !== null && $a['base'] === $b['base'];
    }

    /**
     * Compare two quantities by their UCUM codes after converting to a common base unit.
     *
     * Returns the sign of (a - b) as -1, 0, or 1, or null when the units are not commensurable
     * (different dimension, or either code unrecognised) and therefore cannot be ordered.
     */
    public function compare(float $valueA, string $codeA, float $valueB, string $codeB): ?int
    {
        $a = $this->toBase($codeA, $valueA);
        $b = $this->toBase($codeB, $valueB);

        if ($a === null || $b === null || $a['base'] !== $b['base']) {
            return null;
        }

        return $a['value'] <=> $b['value'];
    }
}
