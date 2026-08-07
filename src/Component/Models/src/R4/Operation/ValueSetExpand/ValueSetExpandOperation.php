<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ValueSetExpand;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSetResource;

#[FhirOperation(
    code: 'expand',
    url: 'http://hl7.org/fhir/OperationDefinition/ValueSet-expand',
    version: 'R4',
    inputClass: ValueSetExpandInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: ValueSetResource::class,
    resource: ['ValueSet'],
    instance: true,
    type: true,
    system: false,
)]
final class ValueSetExpandOperation
{
}
