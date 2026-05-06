<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/time-precision
 *
 * @description Specifies that the precision of the datetime, time, or instant value is less than what appears and indicates the actual level of precision. This extension is used when the precision of a datetime, time, or instant value is not conveyed by the value itself (e.g. the datetime value 2014-02-01T10:00:00Z has a precision of 'min' and actually represents "10:00" and unknown seconds on Feb 1, 2014.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/time-precision', fhirVersion: 'R5')]
class TimePrecisionExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/time-precision',
            value: $this->valueCode,
        );
    }
}
