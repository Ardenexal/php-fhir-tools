<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;

/**
 * Assigns a violation to a named family so fixes can be worked per family rather than per case.
 *
 * A FHIRPath invariant carries its key (ref-1, per-1, ele-1, …), which is the natural family. Other
 * constraints fall back to their Symfony constraint short name. Anything unrecognised lands in
 * "other" rather than being silently dropped, so family sizes always sum to the total.
 */
final class ViolationFamilyClassifier
{
    public const OTHER = 'other';

    public function classify(FHIRValidationViolation $violation): string
    {
        if ($violation->invariantKey !== null && $violation->invariantKey !== '') {
            return 'invariant:' . $violation->invariantKey;
        }

        if ($violation->constraintClass !== '') {
            $tail  = strrchr($violation->constraintClass, '\\');
            $short = $tail === false ? $violation->constraintClass : substr($tail, 1);
            if ($short !== '') {
                return 'constraint:' . $short;
            }
        }

        // Binding failures are reported without an invariant key; the message is the only signal.
        if (str_contains($violation->message, 'not a valid code') || str_contains($violation->message, 'value set')) {
            return 'binding';
        }

        return self::OTHER;
    }

    /**
     * @param list<FHIRValidationViolation> $violations
     *
     * @return list<string> family labels, one per violation, in order
     */
    public function classifyAll(array $violations): array
    {
        return array_map(fn (FHIRValidationViolation $v): string => $this->classify($v), $violations);
    }
}
