<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Logical network location for application activity, if the activity has a network location.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent.network', fhirVersion: 'R4')]
class FHIRAuditEventAgentNetwork extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null address Identifier for the network access point of the user device */
        public \FHIRString|string|null $address = null,
        /** @var FHIRAuditEventAgentNetworkTypeType|null type The type of network access point */
        public ?\FHIRAuditEventAgentNetworkTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
