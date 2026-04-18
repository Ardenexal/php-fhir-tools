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
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsAllele
 *
 * @description Allele information.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAllele', fhirVersion: 'R5')]
class AlleleExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept|null name Name of allele */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $name = null,
        /** @var CodeableConcept|null state The level of occurrence of a single DNA sequence variant within a set of chromosomes: Heteroplasmic / Homoplasmic / Homozygous / Heterozygous / Hemizygous */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $state = null,
        /** @var string|null frequency Allele frequency */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $frequency = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->name !== null) {
            $subExtensions[] = new Extension(url: 'Name', value: $this->name);
        }
        if ($this->state !== null) {
            $subExtensions[] = new Extension(url: 'State', value: $this->state);
        }
        if ($this->frequency !== null) {
            $subExtensions[] = new Extension(url: 'Frequency', value: $this->frequency);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAllele',
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
        $name      = null;
        $state     = null;
        $frequency = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'Name' && $ext->value instanceof CodeableConcept) {
                $name = $ext->value;
            }
            if ($extUrl === 'State' && $ext->value instanceof CodeableConcept) {
                $state = $ext->value;
            }
            if ($extUrl === 'Frequency' && is_string($ext->value)) {
                $frequency = $ext->value;
            }
        }

        return new static($name, $state, $frequency, $id);
    }
}
