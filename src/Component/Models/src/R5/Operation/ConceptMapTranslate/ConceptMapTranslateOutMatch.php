<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ConceptMapTranslate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-translate',
    use: 'out',
    version: 'R5',
    operation: 'ConceptMapTranslate',
    path: 'match',
)]
final class ConceptMapTranslateOutMatch
{
    /**
     * @param list<ConceptMapTranslateOutMatchProperty>  $property
     * @param list<ConceptMapTranslateOutMatchProduct>   $product
     * @param list<ConceptMapTranslateOutMatchDependsOn> $dependsOn
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'relationship',
            phpName: 'relationship',
            use: 'out',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'A code indicating the relationship (e.g., equivalent) of the translation, using values from [ConceptMapRelationship](valueset-concept-map-relationship.html)',
        )]
        public readonly ?string $relationship = null,
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
            name: 'property',
            phpName: 'property',
            use: 'out',
            min: 0,
            max: '*',
            partClass: ConceptMapTranslateOutMatchProperty::class,
            documentation: 'A property of this mapping (may be used to supply for example, mapping priority, provenance, presentation hints, flag as experimental, and additional documentation)',
        )]
        public readonly array $property = [],
        #[FhirOperationParameter(
            name: 'product',
            phpName: 'product',
            use: 'out',
            min: 0,
            max: '*',
            partClass: ConceptMapTranslateOutMatchProduct::class,
            documentation: 'A data value to go in an attribute that is the product of this mapping',
        )]
        public readonly array $product = [],
        #[FhirOperationParameter(
            name: 'dependsOn',
            phpName: 'dependsOn',
            use: 'out',
            min: 0,
            max: '*',
            partClass: ConceptMapTranslateOutMatchDependsOn::class,
            documentation: 'An data value in an additional attribute that this mapping depends on',
        )]
        public readonly array $dependsOn = [],
        #[FhirOperationParameter(
            name: 'originMap',
            phpName: 'originMap',
            use: 'out',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The canonical reference to the concept map from which this mapping comes from',
        )]
        public readonly ?string $originMap = null,
    ) {
    }
}
