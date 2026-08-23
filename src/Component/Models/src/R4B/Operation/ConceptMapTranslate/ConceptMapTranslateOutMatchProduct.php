<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ConceptMapTranslate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-translate',
    use: 'out',
    version: 'R4B',
    operation: 'ConceptMapTranslate',
    path: 'match.product',
)]
final class ConceptMapTranslateOutMatchProduct
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'element',
            phpName: 'element',
            use: 'out',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The element for this product',
        )]
        public readonly ?string $element = null,
        #[FhirOperationParameter(
            name: 'concept',
            phpName: 'concept',
            use: 'out',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'The value for this product',
        )]
        public readonly ?Coding $concept = null,
    ) {
    }
}
