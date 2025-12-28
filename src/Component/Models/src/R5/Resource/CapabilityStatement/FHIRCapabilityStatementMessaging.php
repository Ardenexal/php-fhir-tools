<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A description of the messaging capabilities of the solution.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.messaging', fhirVersion: 'R5')]
class FHIRCapabilityStatementMessaging extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCapabilityStatementMessagingEndpoint> endpoint Where messages should be sent */
        public array $endpoint = [],
        /** @var FHIRUnsignedInt|null reliableCache Reliable Message Cache Length (min) */
        public ?\FHIRUnsignedInt $reliableCache = null,
        /** @var FHIRMarkdown|null documentation Messaging interface behavior details */
        public ?\FHIRMarkdown $documentation = null,
        /** @var array<FHIRCapabilityStatementMessagingSupportedMessage> supportedMessage Messages supported by this system */
        public array $supportedMessage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
