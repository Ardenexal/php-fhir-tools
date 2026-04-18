<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsVariant
 *
 * @description Variant information.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsVariant', fhirVersion: 'R5')]
class VariantExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept|null name HGVS nomenclature for observed DNA sequence variant */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $name = null,
        /** @var CodeableConcept|null idSlice DNA sequence variant ID */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $idSlice = null,
        /** @var CodeableConcept|null type DNA sequence variant type */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->name !== null) {
            $subExtensions[] = new Extension(url: 'Name', value: $this->name);
        }
        if ($this->idSlice !== null) {
            $subExtensions[] = new Extension(url: 'Id', value: $this->idSlice);
        }
        if ($this->type !== null) {
            $subExtensions[] = new Extension(url: 'Type', value: $this->type);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsVariant',
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
        $name    = null;
        $idSlice = null;
        $type    = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'Name' && $ext->value instanceof CodeableConcept) {
                $name = $ext->value;
            }
            if ($extUrl === 'Id' && $ext->value instanceof CodeableConcept) {
                $idSlice = $ext->value;
            }
            if ($extUrl === 'Type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
        }

        return new static($name, $idSlice, $type, $id);
    }
}
