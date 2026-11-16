<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/additional-resource-reference-target
 *
 * @description When defining additional resources, this extension allows the relevant work group to indicate and approve that a core element (or another additional resource) may refer to the resource. This enables normal Reference.reference in deployments where both the core and additional resource definitions are in scope.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/additional-resource-reference-target', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureDefinition')]
class AdditionalResourceReferenceTargetExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var array<Base64BinaryPrimitive> target A reference element that may target this additional resource */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
        public array $target = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        foreach ($this->target as $v) {
            $subExtensions[] = new Extension(url: 'target', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/additional-resource-reference-target',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<Extension> $subExtensions
     * @param string|null      $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): self
    {
        $target = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'target' && $ext->value instanceof Base64BinaryPrimitive) {
                $target[] = $ext->value;
            }
        }

        return new self($target, $id);
    }
}
