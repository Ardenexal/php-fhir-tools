<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Availability;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/extended-contact-availability
 *
 * @description The details provided in this contact may be used according to the attached availability guidelines.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/extended-contact-availability', fhirVersion: 'R5')]
class ExtendedContactAvailabilityExtension extends Extension
{
    public function __construct(
        /** @var Availability|null valueAvailability Value of extension */
        #[FhirProperty(fhirType: 'Availability', propertyKind: 'complex')]
        public ?Availability $valueAvailability = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/extended-contact-availability',
            value: $this->valueAvailability,
        );
    }
}
