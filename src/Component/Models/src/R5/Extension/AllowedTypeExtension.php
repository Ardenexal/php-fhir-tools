<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type
 *
 * @description When the base type is an abstract type (e.g. Resource or Element) then this extension defines which concrete types are allowed to be used for a parameter. In the absence of this extension, any type is allowed. Replaced by OperationDefinition.parameter.allowedType in R5+
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type', fhirVersion: 'R5')]
class AllowedTypeExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|null valueUri Value of extension */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $valueUri = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type',
            value: $this->valueUri,
        );
    }
}
