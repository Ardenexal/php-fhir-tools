<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Class-level constraint that declares one named slice on a sliced array property.
 *
 * FHIR slicing allows a repeating element (e.g. Patient.identifier) to be split into
 * named sub-lists where each item must match a discriminator. This repeatable class-level
 * attribute records one slice's cardinality and discriminator so the companion
 * FHIRSliceConstraintValidator can enforce slice membership at validation time.
 *
 * Profile classes carry one attribute per defined slice, all sharing the profile URL as
 * the validation group. On validation the validator reads all attributes for the same
 * property in a single pass, checking min/max counts and (for closed slicing) rejecting
 * items that match no defined slice.
 *
 * When slicing.ordered = true exists in the profile, enforcement is intentionally deferred
 * (see backlog). The `orderedIndex` field is stored for future use only.
 *
 * Composite discriminators (two discriminators per slicing entry) are not yet supported;
 * the generator emits only the first discriminator and logs a warning.
 *
 * Example:
 * <pre>
 * #[FHIRSliceConstraint(
 *     property: 'identifier',
 *     sliceName: 'ihiNumber',
 *     min: 1,
 *     max: 1,
 *     discriminatorType: 'value',
 *     discriminatorPath: 'system',
 *     discriminatorValue: 'http://ns.electronichealth.net.au/id/hi/ihi/1.0',
 *     groups: ['http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient'],
 * )]
 * class AUCorePatientProfile extends AUBasePatientProfile { ... }
 * </pre>
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRSliceConstraint extends Constraint
{
    /**
     * @param string       $property           Property path on the target class (e.g. 'identifier')
     * @param string       $sliceName          FHIR slice name (e.g. 'ihiNumber'); '@default' for the default slice
     * @param int          $min                Minimum number of items matching this slice (0 = optional)
     * @param int|string   $max                Maximum items ('*' = unbounded, or a positive integer)
     * @param string       $discriminatorType  'value', 'pattern', or 'exists'
     * @param string       $discriminatorPath  Element path within a slice item (e.g. 'system', 'type.coding')
     * @param mixed        $discriminatorValue Value to match at the discriminator path
     * @param list<string> $groups             Profile URL groups under which this slice constraint is active
     * @param bool         $isDefault          True for the special '@default' slice in closed slicing
     * @param int          $orderedIndex       Slice declaration order (reserved for future ordered-slicing enforcement)
     */
    public function __construct(
        public readonly string $property,
        public readonly string $sliceName,
        public readonly int $min = 0,
        public readonly int|string $max = '*',
        public readonly string $discriminatorType = 'value',
        public readonly string $discriminatorPath = '',
        public readonly mixed $discriminatorValue = null,
        ?array $groups = null,
        mixed $payload = null,
        public readonly bool $isDefault = false,
        public readonly int $orderedIndex = 0,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
