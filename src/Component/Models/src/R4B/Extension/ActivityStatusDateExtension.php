<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-activityStatusDate
 *
 * @description The date when the associated Value Set Definition Version activity status is in effect.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-activityStatusDate', fhirVersion: 'R4B')]
class ActivityStatusDateExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|null valueDate Value of extension */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive $valueDate = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-activityStatusDate',
            value: $this->valueDate,
        );
    }
}
