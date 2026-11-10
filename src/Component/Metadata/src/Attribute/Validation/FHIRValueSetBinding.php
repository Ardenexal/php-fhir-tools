<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Binds a coded property to a FHIR ValueSet (StructureDefinition element.binding), recording the
 * binding strength so its value can be checked for membership.
 *
 * `$enumClass` carries the generated backed-enum for this value set, resolved at generation time
 * because it cannot be derived from the URL. Class names come from the ValueSet's `name` field via
 * `ClassNameResolver`, not from the URL slug — `http://hl7.org/fhir/ValueSet/item-type` produces
 * `QuestionnaireItemType`, and `http-verb` produces `HTTPVerb`. The validator previously guessed
 * with `ucwords()` on the slug and missed 27 of 28 value sets, which silently downgraded 19 core
 * required bindings to an unenforced warning.
 *
 * Null when the generator could not resolve an enum (the value set was never materialised, or is
 * unenumerable like `AllLanguages`). Null must behave exactly as before: fall back to derivation and
 * then to the "no enum class generated" warning.
 *
 * `$fromProfile` marks a binding a profile declares (see FHIRProfileBinding). The profile names a
 * value set by URL alone, so only an enum stamped with that URL is trusted, never one guessed from
 * the URL's last segment, and a required binding that nothing can check is reported as unchecked
 * instead of as a missing enum.
 *
 * `$release` ('R4', 'R4B', 'R5') limits the enum lookup to the namespace roots of that release. A
 * value set keeps its URL across releases while its codes change: FHIR-version gains `5.0.0` in R5,
 * so an R5 element checked against the R4 enum would reject a valid code. Null searches every root.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRValueSetBinding extends Constraint
{
    public function __construct(
        public readonly string $valueSetUrl,
        public readonly string $strength = 'required',
        public readonly bool $strict = false,
        public readonly ?string $maxValueSetUrl = null,
        public readonly ?string $enumClass = null,
        ?array $groups = null,
        mixed $payload = null,
        public readonly bool $fromProfile = false,
        public readonly ?string $release = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
