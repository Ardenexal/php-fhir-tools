<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/practitionerrole-employmentStatus
 *
 * @description An indicator of what employment conditions/capacity/entitlements the practitioner is working under - e.g. Full-time, part-time, casual. This is typically a HR related attribute.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/practitionerrole-employmentStatus', fhirVersion: 'R5')]
class PREmploymentStatusExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/practitionerrole-employmentStatus',
            value: $this->valueCodeableConcept,
        );
    }
}
