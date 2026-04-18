<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-protectiveFactor
 *
 * @description Characteristics or strengths of individuals, families, community situations or societies that mitigate risks and promote positivewell-being and healthy development; attributes that help to successfully navigate difficult situations; factors that may contribute to or explain positive outcomes.  A trait or habit that "protects" people and makes them less likely to get a chronic disease that include, but are not limited to exercise, healthy eating, managing weight, managing blood pressure and cholesterol, managing mental health,  feeling happy, strong emotional support and social connections.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-protectiveFactor', fhirVersion: 'R4B')]
class ProtectiveFactorExtension extends Extension
{
    public function __construct(
        /** @var CodeableReference|null valueCodeableReference Value of extension */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $valueCodeableReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-protectiveFactor',
            value: $this->valueCodeableReference,
        );
    }
}
