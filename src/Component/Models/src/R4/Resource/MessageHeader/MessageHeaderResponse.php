<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResponseTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the message that this message is a response to.  Only present if this message is a response.
 */
#[FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.response', fhirVersion: 'R4')]
class MessageHeaderResponse extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IdPrimitive|null identifier Id of original message */
        #[NotBlank]
        public ?IdPrimitive $identifier = null,
        /** @var ResponseTypeType|null code ok | transient-error | fatal-error */
        #[NotBlank]
        public ?ResponseTypeType $code = null,
        /** @var Reference|null details Specific list of hints/warnings/errors */
        public ?Reference $details = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
