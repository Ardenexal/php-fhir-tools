<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Rewrites a number in scientific notation as a plain decimal, digit for digit.
 *
 * bcmath accepts plain decimals only, but a PHP float prints large and small values with an
 * exponent (`1.0E+25`, `1.5E-7`), and a FHIR decimal may carry one too (`1e25`). The rewrite moves
 * the decimal point by the exponent without arithmetic. Fraction digits written in the mantissa
 * stay, so `1.50e1` keeps its significant trailing zero as `15.0`; zeros the shift itself adds before the point
 * carry no precision, so `1e2` is `100`.
 *
 * @internal
 *
 * @author Ardenexal
 */
final class DecimalNotation
{
    private const string SCIENTIFIC = '/\A([+-]?)(\d*)(?:\.(\d*))?[eE]([+-]?\d+)\z/';

    /** Largest exponent rewritten; a larger one would build a string of that many zeros */
    public const int MAX_EXPONENT = 1000;

    /**
     * The plain decimal form of $number, such as '10000000000000000000000000' for '1.0E+25' and
     * '0.00000015' for '1.5E-7'. A number already in plain notation, or a string that is not a
     * number in scientific notation, is returned unchanged.
     *
     * @throws EvaluationException when the exponent is beyond ±{@see MAX_EXPONENT}
     */
    public static function plain(string $number): string
    {
        if (preg_match(self::SCIENTIFIC, $number, $parts) !== 1 || $parts[2] . $parts[3] === '') {
            return $number;
        }

        // The exponent group always matches, so PCRE reports the skipped fraction group as ''
        [, $sign, $integer, $fraction, $exponent] = $parts;

        // Compare the digits as text: an exponent past PHP_INT_MAX would not survive an int cast
        $magnitude = ltrim($exponent, '+-0');
        if (strlen($magnitude) > strlen((string) self::MAX_EXPONENT) || (int) $magnitude > self::MAX_EXPONENT) {
            throw new EvaluationException(sprintf('Cannot rewrite %s as a plain decimal: the exponent is beyond ±%d', $number, self::MAX_EXPONENT));
        }

        $digits = $integer . $fraction;
        $point  = strlen($integer) + (int) $exponent;

        if ($point <= 0) {
            $whole = '0';
            $rest  = str_repeat('0', -$point) . $digits;
        } elseif ($point >= strlen($digits)) {
            $whole = $digits . str_repeat('0', $point - strlen($digits));
            $rest  = '';
        } else {
            $whole = substr($digits, 0, $point);
            $rest  = substr($digits, $point);
        }

        // $rest ends with the mantissa's last written digit, so its trailing zeros were written and
        // stay. Leading zeros before the point carry nothing.
        $whole = ltrim($whole, '0');
        $plain = ($whole === '' ? '0' : $whole) . ($rest === '' ? '' : '.' . $rest);

        // A zero has no sign: '-0e5' is '0', not '-0'
        $isZero = trim($digits, '0') === '';

        return ($sign === '-' && !$isZero ? '-' : '') . $plain;
    }
}
