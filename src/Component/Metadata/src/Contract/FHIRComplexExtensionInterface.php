<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Contract;

/**
 * Contract for typed PHP classes that represent FHIR complex extensions.
 *
 * A complex extension is a StructureDefinition with:
 *   - type: "Extension"
 *   - derivation: "constraint"
 *   - One or more sub-extension slices (Extension.extension elements with a sliceName)
 *
 * Implementing classes carry typed promoted constructor properties for each sub-extension
 * slice (e.g. $value, $period, $comment) and implement this interface so that the
 * serializer can reconstruct typed objects from raw sub-extension arrays during
 * deserialization.
 *
 * @author Ardenexal
 *
 * @see FHIRExtensionInterface
 */
interface FHIRComplexExtensionInterface extends FHIRExtensionInterface
{
    /**
     * Reconstruct a typed complex extension from an array of already-denormalized
     * sub-extension objects.
     *
     * Each sub-extension is matched by its URL (getExtensionUrl()) to the corresponding
     * typed parameter. Array-typed slices accumulate all matching sub-extensions.
     * Sub-extensions with unrecognised URLs are silently ignored.
     *
     * @param array<FHIRExtensionInterface> $subExtensions Denormalized sub-extension objects
     * @param string|null                   $id            Optional element id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static;
}
