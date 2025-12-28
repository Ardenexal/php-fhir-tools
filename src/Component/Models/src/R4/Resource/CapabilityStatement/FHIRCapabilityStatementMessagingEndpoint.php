<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An endpoint (network accessible address) to which messages and/or replies are to be sent.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.messaging.endpoint', fhirVersion: 'R4')]
class FHIRCapabilityStatementMessagingEndpoint extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null protocol http | ftp | mllp + */
        #[NotBlank]
        public ?\FHIRCoding $protocol = null,
        /** @var FHIRUrl|null address Network address or identifier of the end-point */
        #[NotBlank]
        public ?\FHIRUrl $address = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
