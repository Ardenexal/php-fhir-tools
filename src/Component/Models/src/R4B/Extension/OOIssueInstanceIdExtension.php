<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/operationoutcome-instance-id
 *
 * @description A unique identifier for this particular occurrence of the issue in the source system. This may allow tying a reported issue to a record in the source system's audit log.  Included for traceability issues.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/operationoutcome-instance-id', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationOutcome.issue')]
class OOIssueInstanceIdExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/operationoutcome-instance-id',
            value: $this->valueString,
        );
    }
}
