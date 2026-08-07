<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ConceptMapTranslate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-translate',
    use: 'in',
    version: 'R4',
    operation: 'ConceptMapTranslate',
    path: 'dependency',
)]
final class ConceptMapTranslateInDependency
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'element',
            phpName: 'element',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The element for this dependency',
        )]
        public readonly ?string $element = null,
        #[FhirOperationParameter(
            name: 'concept',
            phpName: 'concept',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'The value for this dependency',
        )]
        public readonly ?CodeableConcept $concept = null,
    ) {
    }
}
