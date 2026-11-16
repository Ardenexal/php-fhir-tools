<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CoverageEligibilityRequestSubmit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CoverageEligibilityRequest-submit',
    use: 'in',
    version: 'R4',
    operation: 'CoverageEligibilityRequestSubmit',
    path: '',
)]
final class CoverageEligibilityRequestSubmitInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'An EligibilityRequest resource or Bundle of EligibilityRequests, either as individual EligibilityRequest resources or as Bundles each containing a single EligibilityRequest plus referenced resources.',
        )]
        public readonly ?AbstractResource $resource = null,
    ) {
    }
}
