<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ClaimSubmit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperation(
    code: 'submit',
    url: 'http://hl7.org/fhir/OperationDefinition/Claim-submit',
    version: 'R5',
    inputClass: ClaimSubmitInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['Claim'],
    instance: false,
    type: true,
    system: false,
)]
final class ClaimSubmitOperation
{
}
