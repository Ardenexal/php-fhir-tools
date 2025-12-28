<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Text, attachment(s), or resource(s) to be communicated to the recipient.
 */
#[FHIRBackboneElement(parentResource: 'CommunicationRequest', elementPath: 'CommunicationRequest.payload', fhirVersion: 'R5')]
class FHIRCommunicationRequestPayload extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRAttachment|FHIRReference|FHIRCodeableConcept|null contentX Message part content */
        #[NotBlank]
        public \FHIRAttachment|\FHIRReference|\FHIRCodeableConcept|null $contentX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
