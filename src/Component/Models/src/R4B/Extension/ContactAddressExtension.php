<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-contactAddress
 *
 * @description The address of the contributor.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-contactAddress', fhirVersion: 'R4B')]
class ContactAddressExtension extends Extension
{
    public function __construct(
        /** @var Address|null valueAddress Value of extension */
        #[FhirProperty(fhirType: 'Address', propertyKind: 'complex')]
        public ?Address $valueAddress = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-contactAddress',
            value: $this->valueAddress,
        );
    }
}
