<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventAgentNetworkTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Logical network location for application activity, if the activity has a network location.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent.network', fhirVersion: 'R4')]
class AuditEventAgentNetwork extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null address Identifier for the network access point of the user device */
        public StringPrimitive|string|null $address = null,
        /** @var AuditEventAgentNetworkTypeType|null type The type of network access point */
        public ?AuditEventAgentNetworkTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
