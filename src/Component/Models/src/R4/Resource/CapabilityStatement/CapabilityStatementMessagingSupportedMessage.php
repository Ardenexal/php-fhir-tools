<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EventCapabilityModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description References to message definitions for messages this system can send or receive.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.messaging.supportedMessage', fhirVersion: 'R4')]
class CapabilityStatementMessagingSupportedMessage extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var EventCapabilityModeType|null mode sender | receiver */
        #[NotBlank]
        public ?EventCapabilityModeType $mode = null,
        /** @var CanonicalPrimitive|null definition Message supported by this system */
        #[NotBlank]
        public ?CanonicalPrimitive $definition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
