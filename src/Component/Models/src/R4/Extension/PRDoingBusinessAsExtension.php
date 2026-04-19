<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/practitionerrole-doingBusinessAs
 *
 * @description The name the individual is known by in this role.
 *
 * For example, if a clinician starts their career providing care at Clinic A, and is well known using their maiden name, but then is married, and starts working at Clinic B, but wants to use their married name at Clinic B.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/practitionerrole-doingBusinessAs', fhirVersion: 'R4')]
class PRDoingBusinessAsExtension extends Extension
{
    public function __construct(
        /** @var HumanName|null valueHumanName Value of extension */
        #[FhirProperty(fhirType: 'HumanName', propertyKind: 'complex')]
        public ?HumanName $valueHumanName = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/practitionerrole-doingBusinessAs',
            value: $this->valueHumanName,
        );
    }
}
