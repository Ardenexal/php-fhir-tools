<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc / openEHR project
 *
 * @see http://hl7.org/fhir/StructureDefinition/openEHR-exposureDate
 *
 * @description Record of the date and/or time of the first exposure to the Substance for this Reaction Event.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/openEHR-exposureDate', fhirVersion: 'R4B')]
class ExposureDateExtension extends Extension
{
    public function __construct(
        /** @var DateTimePrimitive|null valueDateTime Value of extension */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive $valueDateTime = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/openEHR-exposureDate',
            value: $this->valueDateTime,
        );
    }
}
