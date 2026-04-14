<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc / openEHR project
 *
 * @see http://hl7.org/fhir/StructureDefinition/openEHR-administration
 *
 * @description Link to an actual medication administration record with the full details of the administration, if a link is known.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/openEHR-administration', fhirVersion: 'R4')]
class AdministrationExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/openEHR-administration',
            value: $this->valueReference,
        );
    }
}
