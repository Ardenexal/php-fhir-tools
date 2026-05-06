<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/familymemberhistory-patient-record
 *
 * @description A link to one to more patient records for the relation.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-patient-record', fhirVersion: 'R4')]
class FMHPatientRecordExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-patient-record',
            value: $this->valueReference,
        );
    }
}
