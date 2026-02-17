<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The destination application which the message is intended for.
 */
#[FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.destination', fhirVersion: 'R4')]
class MessageHeaderDestination extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Name of system */
        public StringPrimitive|string|null $name = null,
        /** @var Reference|null target Particular delivery destination within the destination */
        public ?Reference $target = null,
        /** @var UrlPrimitive|null endpoint Actual destination address or id */
        #[NotBlank]
        public ?UrlPrimitive $endpoint = null,
        /** @var Reference|null receiver Intended "real-world" recipient for the data */
        public ?Reference $receiver = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
