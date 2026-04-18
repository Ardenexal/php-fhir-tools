<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/flag-detail
 *
 * @description Points to the Observation, AllergyIntolerance or other record that provides additional supporting information about this flag. Note that This extension will be replaced by Flag.supportingInfo in the FHIR R6 release.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/flag-detail', fhirVersion: 'R5')]
class FlagDetailExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/flag-detail',
            value: $this->valueReference,
        );
    }
}
