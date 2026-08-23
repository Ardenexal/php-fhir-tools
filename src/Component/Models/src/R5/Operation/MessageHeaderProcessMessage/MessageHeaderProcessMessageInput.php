<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MessageHeaderProcessMessage;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/MessageHeader-process-message',
    use: 'in',
    version: 'R5',
    operation: 'MessageHeaderProcessMessage',
    path: '',
)]
final class MessageHeaderProcessMessageInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'content',
            phpName: 'content',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Bundle',
            documentation: 'The message to process (or, if using asynchronous messaging, it may be a response message to accept)',
        )]
        public readonly ?BundleResource $content = null,
        #[FhirOperationParameter(
            name: 'async',
            phpName: 'async',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'If \'true\' the message is processed using the asynchronous messaging pattern',
        )]
        public readonly ?bool $async = null,
        #[FhirOperationParameter(
            name: 'response-url',
            phpName: 'responseUrl',
            use: 'in',
            min: 0,
            max: '1',
            type: 'url',
            documentation: 'A URL to submit response messages to, if asynchronous messaging is being used, and if the MessageHeader.source.endpoint is not the appropriate place to submit responses',
        )]
        public readonly ?string $responseUrl = null,
    ) {
    }
}
