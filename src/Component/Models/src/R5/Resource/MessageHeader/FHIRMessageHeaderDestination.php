<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl;

/**
 * @description The destination application which the message is intended for.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.destination', fhirVersion: 'R5')]
class FHIRMessageHeaderDestination extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUrl|FHIRReference|null endpointX Actual destination address or Endpoint resource */
        public FHIRUrl|FHIRReference|null $endpointX = null,
        /** @var FHIRString|string|null name Name of system */
        public FHIRString|string|null $name = null,
        /** @var FHIRReference|null target Particular delivery destination within the destination */
        public ?FHIRReference $target = null,
        /** @var FHIRReference|null receiver Intended "real-world" recipient for the data */
        public ?FHIRReference $receiver = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
