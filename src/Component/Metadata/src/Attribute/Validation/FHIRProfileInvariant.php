<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * A profile's FHIRPath invariant (ElementDefinition.constraint) on the element at `$path`.
 *
 * A profile cannot put attributes on the properties it inherits, so its invariants sit on the
 * class and name the element they apply to. The expression is evaluated once per occurrence of
 * that element, with `%resource` bound to the resource around it. An empty path means the class
 * itself: the profiled resource, data type or extension.
 *
 * Assign the profile URL as the validation group, as for FHIRProfileConstraint:
 *
 * <pre>
 * #[FHIRProfileInvariant(
 *     path: 'component',
 *     key: 'vs-3',
 *     severity: 'error',
 *     expression: 'value.exists() or dataAbsentReason.exists()',
 *     human: 'A component without a value needs a data absent reason',
 *     groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
 * )]
 * </pre>
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRProfileInvariant extends Constraint
{
    /**
     * @param string            $path         Element path from the class, e.g. 'component' or 'effective[x]'; '' for the class itself
     * @param string            $key          The invariant's key, e.g. 'vs-3'
     * @param string            $severity     'error' or 'warning'
     * @param string            $expression   FHIRPath expression that must hold for each occurrence
     * @param string            $human        The invariant's description, used as the finding's message
     * @param list<string>|null $groups       Validation groups (profile URLs) under which this invariant is active
     * @param bool              $bestPractice Whether the invariant carries `elementdefinition-bestpractice`; see FHIRPathInvariant
     */
    public function __construct(
        public readonly string $path,
        public readonly string $key,
        public readonly string $severity,
        public readonly string $expression,
        public readonly string $human,
        ?array $groups = null,
        mixed $payload = null,
        public readonly bool $bestPractice = false,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
