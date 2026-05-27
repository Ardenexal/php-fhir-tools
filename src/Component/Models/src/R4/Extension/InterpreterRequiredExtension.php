<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-interpreterRequired
 *
 * @description Indicates whether an interpreter is required to facilitate communication in a healthcare setting. While this extension is named patient-interpreterRequired for backwards compatibility, it may also be used on RelatedPerson, Practitioner, Encounter, Appointment and ServiceRequest.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-interpreterRequired', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'Patient')]
#[FHIRExtensionContext(type: 'element', expression: 'RelatedPerson')]
#[FHIRExtensionContext(type: 'element', expression: 'Encounter')]
#[FHIRExtensionContext(type: 'element', expression: 'Practitioner')]
#[FHIRExtensionContext(type: 'element', expression: 'Appointment')]
#[FHIRExtensionContext(type: 'element', expression: 'ServiceRequest')]
class InterpreterRequiredExtension extends Extension
{
    public function __construct(
        /** @var bool|null valueBoolean Value of extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $valueBoolean = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-interpreterRequired',
            value: $this->valueBoolean,
        );
    }
}
