<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-workflowStatus
 *
 * @description Workflow Status is used to represent details of the value set development process while the value set has an Activity Status of Preliminary. The development of a value set often follows a formal workflow process from initiation to completion, and this element carries the state variable for this state machine. The assumption is that when first created a value set would have a workflow state of Draft. Additional workflow states may be used.  This extension has been deprecated as it is an erroneous duplicate of valueset-workflowStatusDesription.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-workflowStatus', fhirVersion: 'R4B')]
class CSWorkflowStatusExtension extends Extension
{
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
            url: 'http://hl7.org/fhir/StructureDefinition/codesystem-workflowStatus',
            value: $this->valueString,
        );
    }
}
