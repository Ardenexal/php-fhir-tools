<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-haploid
 *
 * @description haploid.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-haploid', fhirVersion: 'R4B')]
class HaploidExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept|null locus haploid-locus */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $locus = null,
        /** @var CodeableConcept|null type haploid-type */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null method haploid-method */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $method = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->locus !== null) {
            $subExtensions[] = new Extension(url: 'locus', value: $this->locus);
        }
        if ($this->type !== null) {
            $subExtensions[] = new Extension(url: 'type', value: $this->type);
        }
        if ($this->method !== null) {
            $subExtensions[] = new Extension(url: 'method', value: $this->method);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-haploid',
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
        $locus  = null;
        $type   = null;
        $method = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'locus' && $ext->value instanceof CodeableConcept) {
                $locus = $ext->value;
            }
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'method' && $ext->value instanceof CodeableConcept) {
                $method = $ext->value;
            }
        }

        return new static($locus, $type, $method, $id);
    }
}
