<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\MessageHeaderProcessMessage;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BundleResource;

#[FhirOperation(
    code: 'process-message',
    url: 'http://hl7.org/fhir/OperationDefinition/MessageHeader-process-message',
    version: 'R4B',
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
