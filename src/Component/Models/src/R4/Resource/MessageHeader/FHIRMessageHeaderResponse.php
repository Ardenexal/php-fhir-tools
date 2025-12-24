<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the message that this message is a response to.  Only present if this message is a response.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.response', fhirVersion: 'R4')]
class FHIRMessageHeaderResponse extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null identifier Id of original message */
        #[NotBlank]
        public ?FHIRId $identifier = null,
        /** @var FHIRResponseTypeType|null code ok | transient-error | fatal-error */
        #[NotBlank]
        public ?FHIRResponseTypeType $code = null,
        /** @var FHIRReference|null details Specific list of hints/warnings/errors */
        public ?FHIRReference $details = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
