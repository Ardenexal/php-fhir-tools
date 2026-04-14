<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - CDS WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-recipientType
 *
 * @description The type of individual that will consume the response content. This may be different from the requesting user type (e.g. if a clinician is getting disease management guidance for provision to a patient). E.g. patient, healthcare provider or specific type of healthcare provider (physician, nurse, etc.).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-recipientType', fhirVersion: 'R4B')]
class RecipientTypeExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-recipientType',
            value: $this->valueCodeableConcept,
        );
    }
}
