<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-nationality
 *
 * @description The nationality of the patient.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-nationality', fhirVersion: 'R4')]
class NationalityExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept|null code Nationality Code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
        /** @var Period|null period Nationality Period */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->code !== null) {
            $subExtensions[] = new Extension(url: 'code', value: $this->code);
        }
        if ($this->period !== null) {
            $subExtensions[] = new Extension(url: 'period', value: $this->period);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-nationality',
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
        $code   = null;
        $period = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'code' && $ext->value instanceof CodeableConcept) {
                $code = $ext->value;
            }
            if ($extUrl === 'period' && $ext->value instanceof Period) {
                $period = $ext->value;
            }
        }

        return new static($code, $period, $id);
    }
}
