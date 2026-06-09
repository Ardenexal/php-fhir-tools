<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/request-performerOrder
 *
 * @description Identifies the relative preference of alternative performers when the request lists multiple performers.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/request-performerOrder', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'ServiceRequest.performer')]
#[FHIRExtensionContext(type: 'element', expression: 'Task.requestedPerformer')]
class PerformerOrderExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var int|null valueInteger Value of extension */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $valueInteger = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/request-performerOrder',
            value: $this->valueInteger,
        );
    }
}
