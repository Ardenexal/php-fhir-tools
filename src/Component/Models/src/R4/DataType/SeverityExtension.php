<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/familymemberhistory-severity
 *
 * @description A qualification of the seriousness or impact on health of the family member condition.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-severity', fhirVersion: 'R4')]
class SeverityExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-severity',
            value: $this->valueCodeableConcept,
        );
    }
}
