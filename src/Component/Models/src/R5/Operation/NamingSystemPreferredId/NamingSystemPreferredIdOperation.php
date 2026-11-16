<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\NamingSystemPreferredId;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'preferred-id',
    url: 'http://hl7.org/fhir/OperationDefinition/NamingSystem-preferred-id',
    version: 'R5',
    inputClass: NamingSystemPreferredIdInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: NamingSystemPreferredIdOutput::class,
    resource: ['NamingSystem'],
    instance: false,
    type: true,
    system: false,
)]
final class NamingSystemPreferredIdOperation
{
}
