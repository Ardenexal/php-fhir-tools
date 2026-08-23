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
    path: 'match',
)]
final class ConceptMapTranslateOutMatch
{
    /**
     * @param list<ConceptMapTranslateOutMatchProduct> $product
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'equivalence',
            phpName: 'equivalence',
            use: 'out',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'A code indicating the equivalence of the translation, using values from [ConceptMapEquivalence](valueset-concept-map-equivalence.html)',
        )]
        public readonly ?string $equivalence = null,
        #[FhirOperationParameter(
            name: 'concept',
            phpName: 'concept',
            use: 'out',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'The translation outcome. Note that this would never have userSelected = true, since the process of translations implies that the user is not selecting the code (and only the client could know differently)',
        )]
        public readonly ?Coding $concept = null,
        #[FhirOperationParameter(
            name: 'product',
            phpName: 'product',
            use: 'out',
            min: 0,
            max: '*',
            partClass: ConceptMapTranslateOutMatchProduct::class,
            documentation: 'Another element that is the product of this mapping',
        )]
        public readonly array $product = [],
        #[FhirOperationParameter(
            name: 'source',
            phpName: 'source',
            use: 'out',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The canonical reference to the concept map from which this mapping comes from',
        )]
        public readonly ?string $source = null,
    ) {
    }
}
