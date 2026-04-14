<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-steward
 *
 * @description The entity that is responsible for the content of the Value Set Definition. This is a textual description of the organizational entity responsible for the content and maintenance.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-steward', fhirVersion: 'R4B')]
class StewardExtension extends Extension
{
    public function __construct(
        /** @var ContactDetail|null valueContactDetail Value of extension */
        #[FhirProperty(fhirType: 'ContactDetail', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail $valueContactDetail = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-steward',
            value: $this->valueContactDetail,
        );
    }
}
