<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIREventCapabilityModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description References to message definitions for messages this system can send or receive.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.messaging.supportedMessage', fhirVersion: 'R4')]
class FHIRCapabilityStatementMessagingSupportedMessage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIREventCapabilityModeType|null mode sender | receiver */
        #[NotBlank]
        public ?FHIREventCapabilityModeType $mode = null,
        /** @var FHIRCanonical|null definition Message supported by this system */
        #[NotBlank]
        public ?FHIRCanonical $definition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
