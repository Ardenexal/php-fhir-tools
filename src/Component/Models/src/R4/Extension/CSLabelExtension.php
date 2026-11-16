<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-label
 *
 * @description This extension is for use when rendering lists of code choices for human selection (typically in Questionnaires). It allows specifying a prefix to put in front of the display name of the code display value. For example "a)", or "iii." or "1]". While in most situations the similar [valueset-label](StructureDefinition-valueset-label.html) extension would be used instead, if the value set is intensionally defined as "all codes from code system [some code system]", then the labels need to appear in the CodeSystem rather than the ValueSet. Note that this will generally not work well for intensional value sets with filters as the labels would usually end up not being sequential.
 *
 * Prefixes are particularly relevant if there are textual instructions that accompany the question. E.g. 'Do not select more than one of options a, b, or c' on a multi-select question.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-label', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'CodeSystem.concept')]
class CSLabelExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/codesystem-label',
            value: $this->valueString,
        );
    }
}
