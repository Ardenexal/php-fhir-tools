<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/condition-dueTo
 *
 * @description Further conditions, problems, diagnoses, procedures or events or the substance that caused/triggered this Condition.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/condition-dueTo', fhirVersion: 'R4')]
class DueToExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|Reference|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/condition-dueTo',
            value: $this->value,
        );
    }
}
