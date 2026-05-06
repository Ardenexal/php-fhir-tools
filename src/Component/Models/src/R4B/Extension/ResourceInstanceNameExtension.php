<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/resource-instance-name
 *
 * @description A natural language name for non-conformance and non-terminology resource instances that is represented in the resource for publication use. Note that this extension only used as part of the IG publication tooling process. Use the [Artifact Title extension](StructureDefinition-artifact-title.html) extension for use outside the IG publishing framework.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/resource-instance-name', fhirVersion: 'R4B')]
class ResourceInstanceNameExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/resource-instance-name',
            value: $this->valueString,
        );
    }
}
