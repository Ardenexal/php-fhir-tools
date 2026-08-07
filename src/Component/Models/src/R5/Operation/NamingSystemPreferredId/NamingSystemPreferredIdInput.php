<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\NamingSystemPreferredId;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/NamingSystem-preferred-id',
    use: 'in',
    version: 'R5',
    operation: 'NamingSystemPreferredId',
    path: '',
)]
final class NamingSystemPreferredIdInput
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
        #[FhirOperationParameter(name: 'type', phpName: 'type', use: 'in', min: 1, max: '1', type: 'code')]
        public readonly ?string $type = null,
        #[FhirOperationParameter(
            name: 'date',
            phpName: 'date',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'If specified, the operation will indicate what the preferred identifier was on the specified date.  If not specified, the operation will provide the preferred identifier as of \'now\'',
        )]
        public readonly ?string $date = null,
    ) {
    }
}
