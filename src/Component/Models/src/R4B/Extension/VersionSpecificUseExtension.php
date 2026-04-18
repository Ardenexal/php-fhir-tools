<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/version-specific-use
 *
 * @description Identifies that the element carrying this extension is only a correct value for a particular range of FHIR versions. This extension is found in contexts where a definition is applying to more than one version, usually defining extensions, and should only be used in context that clearly document how a cross-version definition is used. While there are no limitations to where this extension can be used, known uses are: StructureDefinition.context, ElementDefinition.type, ElementDefinition.additionalBinding and ...
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/version-specific-use', fhirVersion: 'R4B')]
class VersionSpecificUseExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive|null startFhirVersion Starting Version */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $startFhirVersion = null,
        /** @var CodePrimitive|null endFhirVersion Ending Version */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $endFhirVersion = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->startFhirVersion !== null) {
            $subExtensions[] = new Extension(url: 'startFhirVersion', value: $this->startFhirVersion);
        }
        if ($this->endFhirVersion !== null) {
            $subExtensions[] = new Extension(url: 'endFhirVersion', value: $this->endFhirVersion);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/version-specific-use',
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
        $startFhirVersion = null;
        $endFhirVersion   = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'startFhirVersion' && $ext->value instanceof CodePrimitive) {
                $startFhirVersion = $ext->value;
            }
            if ($extUrl === 'endFhirVersion' && $ext->value instanceof CodePrimitive) {
                $endFhirVersion = $ext->value;
            }
        }

        return new static($startFhirVersion, $endFhirVersion, $id);
    }
}
