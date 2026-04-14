<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/resource-approvalDate
 *
 * @description The date on which the asset content was approved by the publisher. Approval happens once when the content is officially approved for usage.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/resource-approvalDate', fhirVersion: 'R4')]
class ApprovalDateExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|null valueDate Value of extension */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $valueDate = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/resource-approvalDate',
            value: $this->valueDate,
        );
    }
}
