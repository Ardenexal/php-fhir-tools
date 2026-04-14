<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - Clinical Quality Information WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqm-ValidityPeriod
 *
 * @description The period in which the catalog is valid.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqm-ValidityPeriod', fhirVersion: 'R4')]
class ValidityPeriodExtension extends Extension
{
    public function __construct(
        /** @var DateTimePrimitive|null valueDateTime Value of extension */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $valueDateTime = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqm-ValidityPeriod',
            value: $this->valueDateTime,
        );
    }
}
