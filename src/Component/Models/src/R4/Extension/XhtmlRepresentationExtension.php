<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/rendering-xhtml
 *
 * @description This is an equivalent of the string on which the extension is sent, but includes additional XHTML markup, such as bold, italics, styles, tables, etc. Existing [restrictions on XHTML content](narrative.html#security) apply. Note that using [markdown](StructureDefinition-rendering-markdown.html) allows for greater flexibility of display. Like the [Resource Narrative](narrative.html), this extension may reference binary resources for image content (see note about [referencing images](narrative.html#id)).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/rendering-xhtml', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'string')]
#[FHIRProfileInvariant(
    path: 'value[x]',
    key: 'xhtml-ext-1',
    severity: 'error',
    expression: 'htmlChecks()',
    human: 'The XHTML contained here SHALL abide by the same constraints as narrative.  I.e. contain only the basic html formatting elements and attributes described in chapters 7-11 (except section 4 of chapter 9) and 15 of the HTML 4.0 standard, <a> elements (either name or href), images and internally contained style attributes',
)]
class XhtmlRepresentationExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/rendering-xhtml',
            value: $this->valueString,
        );
    }
}
