<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/servicerequest-precondition
 *
 * @description The condition or state of the patient, prior or during the diagnostic procedure or test, for example, fasting, at-rest, or post-operative. This captures circumstances that may influence the measured value and have bearing on the interpretation of the result.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-precondition', fhirVersion: 'R4B')]
class PreconditionExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-precondition',
            value: $this->valueCodeableConcept,
        );
    }
}
