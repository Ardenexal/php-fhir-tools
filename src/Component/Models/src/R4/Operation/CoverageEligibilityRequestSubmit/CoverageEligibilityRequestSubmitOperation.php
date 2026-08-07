<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CoverageEligibilityRequestSubmit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;

#[FhirOperation(
    code: 'submit',
    url: 'http://hl7.org/fhir/OperationDefinition/CoverageEligibilityRequest-submit',
    version: 'R4',
    inputClass: CoverageEligibilityRequestSubmitInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['CoverageEligibilityRequest'],
    instance: false,
    type: true,
    system: false,
)]
final class CoverageEligibilityRequestSubmitOperation
{
}
