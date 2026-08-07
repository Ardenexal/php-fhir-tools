<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\NamingSystemTranslateId;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'translate-id',
    url: 'http://hl7.org/fhir/OperationDefinition/NamingSystem-translate-id',
    version: 'R5',
    inputClass: NamingSystemTranslateIdInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: NamingSystemTranslateIdOutput::class,
    resource: ['NamingSystem'],
    instance: false,
    type: true,
    system: false,
)]
final class NamingSystemTranslateIdOperation
{
}
