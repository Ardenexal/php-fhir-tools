<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description A description of the messaging capabilities of the solution.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.messaging', fhirVersion: 'R4')]
class CapabilityStatementMessaging extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CapabilityStatementMessagingEndpoint> endpoint Where messages should be sent */
        public array $endpoint = [],
        /** @var UnsignedIntPrimitive|null reliableCache Reliable Message Cache Length (min) */
        public ?UnsignedIntPrimitive $reliableCache = null,
        /** @var MarkdownPrimitive|null documentation Messaging interface behavior details */
        public ?MarkdownPrimitive $documentation = null,
        /** @var array<CapabilityStatementMessagingSupportedMessage> supportedMessage Messages supported by this system */
        public array $supportedMessage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
