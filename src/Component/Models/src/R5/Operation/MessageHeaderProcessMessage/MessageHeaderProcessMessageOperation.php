<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MessageHeaderProcessMessage;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'process-message',
    url: 'http://hl7.org/fhir/OperationDefinition/MessageHeader-process-message',
    version: 'R5',
    inputClass: MessageHeaderProcessMessageInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['MessageHeader'],
    instance: false,
    type: false,
    system: true,
)]
final class MessageHeaderProcessMessageOperation
{
}
