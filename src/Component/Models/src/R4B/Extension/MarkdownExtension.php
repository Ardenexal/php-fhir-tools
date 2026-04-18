<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/rendering-markdown
 *
 * @description This is an equivalent of the string on which the extension is sent, but includes additional markdown (see documentation about [markdown](datatypes.html#markdown). Note that using HTML  [xhtml](StructureDefinition-rendering-xhtml.html) can allow for greater precision of display.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/rendering-markdown', fhirVersion: 'R4B')]
class MarkdownExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/rendering-markdown',
            value: $this->valueMarkdown,
        );
    }
}
