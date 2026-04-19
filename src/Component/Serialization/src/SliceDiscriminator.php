<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * Runtime representation of a FHIR slice discriminator registered in FHIRIGTypeRegistry.
 *
 * Holds the information needed to determine whether a raw FHIR data array matches a
 * specific constrained profile class. Created at container compile time by
 * FHIRIGRegistryCompilerPass from #[FHIRSliceDiscriminator] attributes on profile classes.
 *
 * @see \Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRSliceDiscriminator
 * @see FHIRIGTypeRegistry::resolveSliceClass()
 *
 * @author Ardenexal
 */
final readonly class SliceDiscriminator
{
    /**
     * @param string       $type        Discriminator type: 'value' (exact match) or 'pattern' (subset match)
     * @param string       $path        Element path within the slice (e.g. 'system', 'type', 'code')
     * @param mixed        $value       The expected value (string for value type, array for pattern type)
     * @param class-string $targetClass The profile class to instantiate when this discriminator matches
     */
    public function __construct(
        public readonly string $type,
        public readonly string $path,
        public readonly mixed $value,
        public readonly string $targetClass,
    ) {
    }
}
