<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-supportLink
 *
 * @description A URL that resolves to additional supporting information or guidance related to the question. If there's more than one repetition of this extension, all a UI can do to help the user differentiate which to click on is displaying the URI (e.g. as a flyover).  If displaying the URI might not be appropriate or helpful, it may be more useful to use a nested 'display' item of type 'helpText' which can then specify multiple hyperlinks with appropriate labels and/or surrounding guidance text. This extension has been deprecated in favor of `questionnaire-supportHyperlink`, which allows display text to accompany each support link. This extension only conveyed the URI, making it unclear to users what each link referred to.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-supportLink', fhirVersion: 'R4')]
class QSupportLinkExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-supportLink',
            value: $this->valueUri,
        );
    }
}
