<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Translates a FHIR element path into one Symfony's PropertyAccessor can follow.
 *
 * @author Ardenexal
 */
final class FHIRElementPath
{
    /**
     * Strip the choice-element marker so the path names a property that actually exists.
     *
     * A StructureDefinition writes a choice element as `effective[x]`, but the generator emits a single
     * polymorphic property carrying every variant — `public DateTimePrimitive|Period|Timing|InstantPrimitive|null $effective`
     * — with the variants' JSON keys held in its `#[FhirProperty]` metadata. There is no `$effective[x]`
     * and never will be, so a path used verbatim sends the accessor looking for a property that was never
     * generated. The read fails, and a rule the reference validator reports goes silently unevaluated.
     *
     * Only the marker is removed. Array indexes such as `coding[0]` are left alone, because those the
     * accessor does understand.
     *
     * Report violations against the original path rather than this one: the reference validator names the
     * element `Observation.effective[x]`, and dropping the marker there would make our wording diverge
     * from the thing we are being compared against.
     *
     * @param string $path Element path as a StructureDefinition writes it, choice markers and all
     *
     * @return string The same path with choice markers removed, ready for PropertyAccessor
     */
    public static function toPropertyPath(string $path): string
    {
        return str_replace('[x]', '', $path);
    }
}
