<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-label
 *
 * @description This extension is for use when a value set is to be used to render a list of code choices for human selection (typically in Questionnaires). It allows specifying a prefix to put in front of the display name of the code display value. For example "a)", or "iii." or "1]". This only works if the value set is defined extensionally (i.e. enumerating the codes). If defining an intensional value set with the definition "all codes from code system [some code system]", then use the [codesystem-label](StructureDefinition-codesystem-label.html) extension instead.
 *
 * Prefixes are particularly relevant if there are textual instructions that accompany the question. E.g. 'Do not select more than one of options a, b, or c' on a multi-select question.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-label', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'ValueSet.compose.include.concept')]
#[FHIRExtensionContext(type: 'element', expression: 'ValueSet.expansion.contains')]
class VSLabelExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-label',
            value: $this->valueString,
        );
    }
}
