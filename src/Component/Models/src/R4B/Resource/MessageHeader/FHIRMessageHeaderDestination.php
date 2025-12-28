<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The destination application which the message is intended for.
 */
#[FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.destination', fhirVersion: 'R4B')]
class FHIRMessageHeaderDestination extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Name of system */
        public \FHIRString|string|null $name = null,
        /** @var FHIRReference|null target Particular delivery destination within the destination */
        public ?\FHIRReference $target = null,
        /** @var FHIRUrl|null endpoint Actual destination address or id */
        #[NotBlank]
        public ?\FHIRUrl $endpoint = null,
        /** @var FHIRReference|null receiver Intended "real-world" recipient for the data */
        public ?\FHIRReference $receiver = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
