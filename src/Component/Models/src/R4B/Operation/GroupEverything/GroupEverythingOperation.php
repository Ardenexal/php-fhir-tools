<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\GroupEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BundleResource;

#[FhirOperation(
    code: 'everything',
    url: 'http://hl7.org/fhir/OperationDefinition/Group-everything',
    version: 'R4B',
    inputClass: GroupEverythingInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Group'],
    instance: true,
    type: false,
    system: false,
)]
final class GroupEverythingOperation
{
}
