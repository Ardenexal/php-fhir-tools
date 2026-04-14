<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-supportLink
 *
 * @description A URL that resolves to additional supporting information or guidance related to the question.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-supportLink', fhirVersion: 'R4B')]
class SupportLinkExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|null valueUri Value of extension */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive $valueUri = null,
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
