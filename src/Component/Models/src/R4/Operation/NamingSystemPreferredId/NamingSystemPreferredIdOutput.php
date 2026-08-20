<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\NamingSystemPreferredId;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/NamingSystem-preferred-id',
    use: 'out',
    version: 'R4',
    operation: 'NamingSystemPreferredId',
    path: '',
)]
final class NamingSystemPreferredIdOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'result',
            phpName: 'result',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'OIDs are return as plain OIDs (not the URI form).',
        )]
        public readonly ?string $result = null,
    ) {
    }
}
