<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-expirationDate
 *
 * @description The date when the value set version is no longer expected to be used to create new content. This is the first date-time when the value set version becomes Inactive, so this value SHALL present on all Inactive value set versions. The start Date_time is expected to be as of 0001 UTC of the Expiration Date.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-expirationDate', fhirVersion: 'R5')]
class ValueSetExpirationDateExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|null valueDate Value of extension */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $valueDate = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-expirationDate',
            value: $this->valueDate,
        );
    }
}
