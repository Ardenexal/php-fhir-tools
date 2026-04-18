<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/resource-instance-description
 *
 * @description A natural language description for non-conformance and non-terminology resource instances that is represented in the resource for publication use. Note that this extension only used as part of the IG publication tooling process. Use the [Artifact Title extension](StructureDefinition-artifact-description.html) extension for use outside the IG publishing framework.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/resource-instance-description', fhirVersion: 'R5')]
class ResourceInstanceDescriptionExtension extends Extension
{
    public function __construct(
        /** @var MarkdownPrimitive|null valueMarkdown Value of extension */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $valueMarkdown = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/resource-instance-description',
            value: $this->valueMarkdown,
        );
    }
}
