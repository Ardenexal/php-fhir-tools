<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Encodes a profile-specific constraint on a property path, resolved at class-level validation.
 *
 * PHP does not allow re-declaring promoted constructor parameters in subclasses, so profile
 * classes cannot add attributes to inherited properties directly. This repeatable class-level
 * attribute carries the property path, inner Symfony constraint class, and its constructor
 * options. The companion FHIRProfileConstraintValidator reads it and delegates to the inner
 * constraint at the indicated path.
 *
 * Assign the profile URL as the validation group so Symfony activates this constraint only
 * when that group is requested:
 *
 * <pre>
 * #[FHIRProfileConstraint(
 *     path: 'identifier',
 *     constraint: Count::class,
 *     options: ['min' => 1],
 *     groups: ['http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient'],
 * )]
 * class AUCorePatientProfile extends PatientResource { ... }
 * </pre>
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRProfileConstraint extends Constraint
{
    /**
     * @param string                   $path       Property path on the target object (e.g. 'identifier', 'name')
     * @param class-string<Constraint> $constraint Symfony Constraint class to instantiate for inner validation
     * @param array<string, mixed>     $options    Named constructor arguments forwarded to the inner constraint
     * @param list<string>|null        $groups     Validation groups (profile URLs) under which this constraint is active
     */
    public function __construct(
        public readonly string $path,
        public readonly string $constraint,
        public readonly array $options = [],
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
