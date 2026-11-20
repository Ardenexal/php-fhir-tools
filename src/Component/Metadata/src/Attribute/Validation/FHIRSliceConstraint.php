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
 * `orderedIndex` is the slice's position in the slicing's declaration order. When the slicing is
 * ordered (FHIRSlicingRules `ordered`), items must follow that order. A derived profile's group
 * repeats its parent's slices with `inherited` so its own slices are ordered among the parent's.
 *
 * `discriminators` holds every discriminator of the slicing, each with the value this slice
 * expects, and an item belongs to the slice only when it satisfies all of them. A discriminator
 * the generator could find no value for has no `value` key, and no item matches that slice. A
 * slice keyed only by a required binding carries the value set as `valueSet` instead; it matches
 * through a configured terminology client, and without one it matches nothing and its minimum is
 * not checked. A resliced slice that states no value at a discriminator path, leaving each reslice to
 * fix one, carries those reslices' discriminators as `anyOf` instead.
 *
 * Rules the profile places beneath the slice (e.g. a fixed `valueQuantity.system` on one
 * component slice) travel in `rules`, with paths relative to a slice item. They bind only the
 * items that match this slice, which a plain FHIRProfileConstraint on the unsliced path cannot do.
 *
 * Example:
 * <pre>
 * #[FHIRSliceConstraint(
 *     property: 'identifier',
 *     sliceName: 'ihiNumber',
 *     min: 1,
 *     max: 1,
 *     discriminators: [['type' => 'value', 'path' => 'system', 'value' => 'http://ns.electronichealth.net.au/id/hi/ihi/1.0']],
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
     * @param string                                                                                                                                                       $property       Property path on the target class (e.g. 'identifier')
     * @param string                                                                                                                                                       $sliceName      FHIR slice name (e.g. 'ihiNumber'); '@default' for the default slice
     * @param int                                                                                                                                                          $min            Minimum number of items matching this slice (0 = optional)
     * @param int|string                                                                                                                                                   $max            Maximum items ('*' = unbounded, or a positive integer)
     * @param list<array{type: string, path: string, value?: mixed, valueSet?: string, anyOf?: list<array{type: string, path: string, value?: mixed, valueSet?: string}>}> $discriminators Every discriminator of the slicing: its type ('value', 'pattern', 'exists', 'type' or 'profile'),
     *                                                                                                                                                                                     its path within a slice item ('$this' for the item itself), and the value this slice expects there.
     *                                                                                                                                                                                     `valueSet` replaces `value` for a slice keyed only by a required binding, and `anyOf`
     *                                                                                                                                                                                     (its reslices' discriminators, any one of which an item may match) for a resliced slice with no value of its own
     * @param list<string>                                                                                                                                                 $groups         Profile URL groups under which this slice constraint is active
     * @param bool                                                                                                                                                         $isDefault      True for the special '@default' slice in closed slicing
     * @param int                                                                                                                                                          $orderedIndex   Slice declaration order, enforced when the slicing is ordered
     * @param list<array{path: string, constraint: class-string<Constraint|FHIRSlicingRules>, options: array<string, mixed>}>                                              $rules
     *                                                                                                                                                                                     Rules each item matching this slice must meet; `path` is relative to the item.
     *                                                                                                                                                                                     A FHIRSliceConstraint or FHIRSlicingRules rule describes slicing beneath the slice;
     *                                                                                                                                                                                     at path '' it describes the slice's reslices, which split the items matching it
     * @param bool                                                                                                                                                         $inherited      True for a parent profile's slice repeated in a derived profile's group so closed or ordered
     *                                                                                                                                                                                     slicing can place every item; the order among two such slices is the parent group's to report
     */
    public function __construct(
        public readonly string $property,
        public readonly string $sliceName,
        public readonly int $min = 0,
        public readonly int|string $max = '*',
        public readonly array $discriminators = [],
        ?array $groups = null,
        mixed $payload = null,
        public readonly bool $isDefault = false,
        public readonly int $orderedIndex = 0,
        public readonly array $rules = [],
        public readonly bool $inherited = false,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
