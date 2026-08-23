<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ClaimSubmit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Claim-submit',
    use: 'in',
    version: 'R5',
    operation: 'ClaimSubmit',
    path: '',
)]
final class ClaimSubmitInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'A Claim resource or Bundle of claims, either as individual Claim resources or as Bundles each containing a single Claim plus referenced resources.',
        )]
        public readonly ?AbstractResource $resource = null,
    ) {
    }
}
