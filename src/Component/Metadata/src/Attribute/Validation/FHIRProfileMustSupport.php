<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Class-level marker on profile classes for inherited properties declared mustSupport in the
 * profile differential.
 *
 * PHP does not allow re-declaring promoted constructor parameters in subclasses, so profile
 * classes cannot add #[FHIRMustSupport] directly to inherited properties. This repeatable
 * class-level attribute carries the property path and the profile URL groups under which the
 * must-support declaration applies.
 *
 * This is pure metadata — it does NOT extend Symfony's Constraint and has no validator.
 * Consumers read it via:
 *   ReflectionClass::getAttributes(FHIRProfileMustSupport::class)
 *
 * Example:
 * <pre>
 * #[FHIRProfileMustSupport(
 *     path: 'identifier',
 *     groups: ['http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient'],
 * )]
 * class AUCorePatientProfile extends AUBasePatientProfile { ... }
 * </pre>
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRProfileMustSupport
{
    /**
     * @param string       $path   Property path on the target object (e.g. 'identifier', 'name')
     * @param list<string> $groups Profile URLs under which this must-support declaration is active
     */
    public function __construct(
        public readonly string $path,
        public readonly array $groups = [],
    ) {
    }
}
