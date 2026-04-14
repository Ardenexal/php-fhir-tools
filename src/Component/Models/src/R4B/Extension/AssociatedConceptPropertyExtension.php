<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-assocConceptProp
 *
 * @description Concept Properties that are associated with this Code System or Value Set Version
 */
#[FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-assocConceptProp', fhirVersion: 'R4B')]
class AssociatedConceptPropertyExtension extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive name Property name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive $name,
        /** @var StringPrimitive valueSlice Property value */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive $valueSlice,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'name', value: $this->name);
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-assocConceptProp',
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
        $name       = null;
        $valueSlice = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'name' && $ext->value instanceof StringPrimitive) {
                $name = $ext->value;
            }
            if ($extUrl === 'value' && $ext->value instanceof StringPrimitive) {
                $valueSlice = $ext->value;
            }
        }

        return new static($name, $valueSlice, $id);
    }
}
