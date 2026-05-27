<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Class-level marker on profile classes for inherited properties that carry FHIR obligation
 * extensions in the StructureDefinition snapshot.
 *
 * PHP does not allow re-declaring promoted constructor parameters in subclasses, so profile
 * classes cannot add #[FHIRObligation] directly to inherited properties. This repeatable
 * class-level attribute carries the property path, obligation code, and optional actor/filter
 * for tooling and M12 obligation-aware validation.
 *
 * This is pure metadata — it does NOT extend Symfony's Constraint and has no validator.
 * Consumers read it via:
 *   ReflectionClass::getAttributes(FHIRProfileObligation::class)
 *
 * Example:
 * <pre>
 * #[FHIRProfileObligation(
 *     path: 'identifier',
 *     code: 'SHALL:populate',
 *     actor: 'http://example.org/actor/requester',
 *     groups: ['http://hl7.org.au/fhir/core/StructureDefinition/au-erequesting-servicerequest-path'],
 * )]
 * class AUERequestingServiceRequestPathProfile extends ServiceRequestResource { ... }
 * </pre>
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRProfileObligation
{
    /**
     * @param string       $path   Property path on the target object (e.g. 'identifier', 'code')
     * @param string       $code   Obligation code (e.g. 'SHALL:populate')
     * @param string|null  $actor  Optional canonical URL of the actor this obligation applies to
     * @param string|null  $filter Optional filter expression qualifying the obligation scope
     * @param list<string> $groups Profile URLs under which this obligation applies
     */
    public function __construct(
        public readonly string $path,
        public readonly string $code,
        public readonly ?string $actor = null,
        public readonly ?string $filter = null,
        public readonly array $groups = [],
    ) {
    }
}
