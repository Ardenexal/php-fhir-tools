<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Records the FHIR ValueSet a generated enum was built from.
 *
 * This exists so a binding can be *verified* rather than trusted. `#[FHIRValueSetBinding]` carries an
 * `enumClass` resolved at generation time, but the class name is derived from the ValueSet's `name`
 * field, and two different value sets can collide on one name:
 * `.../ValueSet/medication-statement-status` and `.../ValueSet/medication-status` both resolve to
 * `MedicationStatusCodes`, whose generated enum holds only the latter's three codes. Trusting the name
 * alone bound `MedicationStatement.status` to the wrong enum and rejected the legal code `unknown`.
 *
 * With the source URL recorded here, the validator can compare it against the binding's own value set
 * URL and decline to use a mismatched enum — degrading to the existing "no enum class generated"
 * warning instead of rejecting valid codes. The collision becomes safe by construction rather than by
 * the accident that previously masked it.
 *
 * The generator already writes this URL into the enum's docblock; an attribute makes it readable at
 * runtime without parsing comments.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class FHIRValueSetSource
{
    public function __construct(
        public readonly string $url,
        public readonly ?string $version = null,
    ) {
    }
}
