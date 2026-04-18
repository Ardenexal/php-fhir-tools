<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Public Health
 *
 * @see http://hl7.org/fhir/StructureDefinition/immunization-procedure
 *
 * @description A record of the procedure associated with the immunization event.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/immunization-procedure', fhirVersion: 'R4B')]
class ImmProcedureExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/immunization-procedure',
            value: $this->valueCodeableReference,
        );
    }
}
