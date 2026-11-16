<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\NamingSystemTranslateId;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/NamingSystem-translate-id',
    use: 'in',
    version: 'R5',
    operation: 'NamingSystemTranslateId',
    path: '',
)]
final class NamingSystemTranslateIdInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'id',
            phpName: 'id',
            use: 'in',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'The server parses the provided identifier to see what type it is (e.g. a URI, an OID as a URI, a plain OID, or a v2 table 0396 code). If the server can\'t tell what type of identifier it is, it can try it as multiple types. It is an error if more than one system matches the provided identifier',
        )]
        public readonly ?string $id = null,
        #[FhirOperationParameter(name: 'sourceType', phpName: 'sourceType', use: 'in', min: 1, max: '1', type: 'code')]
        public readonly ?string $sourceType = null,
        #[FhirOperationParameter(name: 'targetType', phpName: 'targetType', use: 'in', min: 1, max: '1', type: 'code')]
        public readonly ?string $targetType = null,
        #[FhirOperationParameter(
            name: 'preferredOnly',
            phpName: 'preferredOnly',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'If preferredOnly = true then return only the preferred identifier, or if preferredOnly = false then return all available ids.',
        )]
        public readonly ?bool $preferredOnly = null,
        #[FhirOperationParameter(
            name: 'date',
            phpName: 'date',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'If \'date\' is supplied return only ids that have a validity period that includes that date.',
        )]
        public readonly ?string $date = null,
    ) {
    }
}
