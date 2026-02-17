<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CommunicationRequest;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Text, attachment(s), or resource(s) to be communicated to the recipient.
 */
#[FHIRBackboneElement(parentResource: 'CommunicationRequest', elementPath: 'CommunicationRequest.payload', fhirVersion: 'R4')]
class CommunicationRequestPayload extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|Attachment|Reference|null contentX Message part content */
        #[NotBlank]
        public StringPrimitive|string|Attachment|Reference|null $contentX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
