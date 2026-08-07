<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\NamingSystemTranslateId;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/NamingSystem-translate-id',
    use: 'out',
    version: 'R5',
    operation: 'NamingSystemTranslateId',
    path: '',
)]
final class NamingSystemTranslateIdOutput
{
    /**
     * @param list<string> $targetIdentifier
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'result',
            phpName: 'result',
            use: 'out',
            min: 1,
            max: '1',
            type: 'boolean',
            documentation: 'True if the identifier could be translated successfully.',
        )]
        public readonly ?bool $result = null,
        #[FhirOperationParameter(
            name: 'targetIdentifier',
            phpName: 'targetIdentifier',
            use: 'out',
            min: 0,
            max: '*',
            type: 'string',
            documentation: 'The target identifer(s) of the requested type',
        )]
        public readonly array $targetIdentifier = [],
        #[FhirOperationParameter(
            name: 'targetIdentifer.preferred',
            phpName: 'targetIdentiferPreferred',
            use: 'out',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Whether the target identifier is preferred.',
        )]
        public readonly ?bool $targetIdentiferPreferred = null,
        #[FhirOperationParameter(
            name: 'targetIdentifier.period',
            phpName: 'targetIdentifierPeriod',
            use: 'out',
            min: 0,
            max: '1',
            type: 'Period',
            documentation: 'The perioid when the target identifier is valid.',
        )]
        public readonly ?Period $targetIdentifierPeriod = null,
    ) {
    }
}
