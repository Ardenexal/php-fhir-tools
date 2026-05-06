<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Declares how a profiled type is discriminated from its base type in a sliced list.
 *
 * FHIR slicing allows a repeating element (e.g. Patient.identifier) to be split into
 * typed sub-lists (slices) where each item must conform to different constraints. The
 * discriminator tells processors how to determine which slice an item belongs to.
 *
 * This attribute is repeatable — a profile may have multiple discriminators that form
 * a composite key (all must match for the slice to apply).
 *
 * Supported discriminator types:
 *   - 'value'   — The slice item has a specific value at the given path (e.g. system URI)
 *   - 'pattern' — The slice item contains a pattern value at the given path (e.g. a
 *                 CodeableConcept that must include a particular coding)
 *
 * Example: AU IHI identifier discriminated by system URI value:
 * <pre>
 * #[FHIRSliceDiscriminator(type: 'value', path: 'system', value: 'http://...ihi/1.0')]
 * class AUIHIProfile extends Identifier { ... }
 * </pre>
 *
 * Example: Observation component discriminated by code pattern:
 * <pre>
 * #[FHIRSliceDiscriminator(type: 'pattern', path: 'code', value: ['coding' => [['code' => '8480-6']]])]
 * class SystolicBPComponent extends ObservationComponent { ... }
 * </pre>
 *
 * @see https://www.hl7.org/fhir/profiling.html#discriminator
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRSliceDiscriminator
{
    /**
     * @param string $type  Discriminator type: 'value' or 'pattern'
     * @param string $path  Element path within the slice (e.g. 'system', 'code', 'type.coding.code')
     * @param mixed  $value The value to match against at the given path
     */
    public function __construct(
        public readonly string $type,
        public readonly string $path,
        public readonly mixed $value,
    ) {
    }
}
