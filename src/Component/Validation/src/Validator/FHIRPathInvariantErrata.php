<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

/**
 * Corrections for invariant expressions that a FHIR release published wrongly.
 *
 * Generated models carry each invariant exactly as its release published it, which is right almost
 * always. A few published expressions do not say what the invariant's own human text says, and a
 * later release corrected them. The HL7 Java reference validator substitutes the corrected expression
 * before evaluating (its `FHIRPathExpressionFixer`), so an instance it rejects under the corrected
 * rule passes ours under the published one.
 *
 * Keyed by the published expression text rather than by invariant key, so a correction only applies
 * to the release that published the mistake: R4B and R5 publish the corrected text and never match.
 * Only corrections a corpus case shows the reference validator applying belong here.
 */
final class FHIRPathInvariantErrata
{
    /**
     * Published expression => corrected expression.
     *
     * - `que-12` in R4 counts `> 2`, while its human text is "If there are more than one
     *   enableWhen, enableBehavior must be specified". R4B and R5 publish `> 1`.
     *   `R4.questionnaire-enableWhen-dw` and `R4.q-enablewhen-me-wrong` have two enableWhen each.
     * - `eld-19` in R4 leaves its regex unanchored, so `matches()` accepts any path that merely
     *   contains a legal name, `Observation...unit` (`R4.obs-unit-profile`) among them. R4B and R5
     *   publish the same regex anchored with `^` and `$`, which is the correction used here.
     */
    private const array CORRECTIONS = [
        'enableWhen.count() > 2 implies enableBehavior.exists()'                                                                                                               => 'enableWhen.count() > 1 implies enableBehavior.exists()',
        'path.matches(\'[^\\\s\\\.,:;\\\\\\\'"\\\/|?!@#$%&*()\\\[\\\]{}]{1,64}(\\\.[^\\\s\\\.,:;\\\\\\\'"\\\/|?!@#$%&*()\\\[\\\]{}]{1,64}(\\\[x\\\])?(\\\:[^\\\s\\\.]+)?)*\')' => 'path.matches(\'^[^\\\s\\\.,:;\\\\\\\'"\\\/|?!@#$%&*()\\\[\\\]{}]{1,64}(\\\.[^\\\s\\\.,:;\\\\\\\'"\\\/|?!@#$%&*()\\\[\\\]{}]{1,64}(\\\[x\\\])?(\\\:[^\\\s\\\.]+)?)*$\')',
    ];

    /** The expression to evaluate for a published one: its correction if it has one, else itself. */
    public static function correct(string $expression): string
    {
        return self::CORRECTIONS[$expression] ?? $expression;
    }
}
