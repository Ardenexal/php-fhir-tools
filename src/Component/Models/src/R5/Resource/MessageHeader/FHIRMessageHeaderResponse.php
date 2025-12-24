<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the message that this message is a response to.  Only present if this message is a response.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.response', fhirVersion: 'R5')]
class FHIRMessageHeaderResponse extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Bundle.identifier of original message */
        #[NotBlank]
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRResponseTypeType|null code ok | transient-error | fatal-error */
        #[NotBlank]
        public ?FHIRResponseTypeType $code = null,
        /** @var FHIRReference|null details Specific list of hints/warnings/errors */
        public ?FHIRReference $details = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
