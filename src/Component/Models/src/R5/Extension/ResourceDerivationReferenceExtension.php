<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/derivation-reference
 *
 * @description References a location within a set of source text from which the discrete information described by this Resource/Element was extracted.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/derivation-reference', fhirVersion: 'R5')]
class ResourceDerivationReferenceExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference|null reference Source reference. */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $reference = null,
        /** @var StringPrimitive|null path Element containing text. */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $path = null,
        /** @var int|null offset Starting position. */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $offset = null,
        /** @var int|null length Number of characters. */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $length = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->reference !== null) {
            $subExtensions[] = new Extension(url: 'reference', value: $this->reference);
        }
        if ($this->path !== null) {
            $subExtensions[] = new Extension(url: 'path', value: $this->path);
        }
        if ($this->offset !== null) {
            $subExtensions[] = new Extension(url: 'offset', value: $this->offset);
        }
        if ($this->length !== null) {
            $subExtensions[] = new Extension(url: 'length', value: $this->length);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/derivation-reference',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $reference = null;
        $path      = null;
        $offset    = null;
        $length    = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'reference' && $ext->value instanceof Reference) {
                $reference = $ext->value;
            }
            if ($extUrl === 'path' && $ext->value instanceof StringPrimitive) {
                $path = $ext->value;
            }
            if ($extUrl === 'offset' && is_int($ext->value)) {
                $offset = $ext->value;
            }
            if ($extUrl === 'length' && is_int($ext->value)) {
                $length = $ext->value;
            }
        }

        return new static($reference, $path, $offset, $length, $id);
    }
}
