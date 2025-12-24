<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates what types of messages may be sent as an application-level response to this message.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageDefinition', elementPath: 'MessageDefinition.allowedResponse', fhirVersion: 'R4')]
class FHIRMessageDefinitionAllowedResponse extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCanonical|null message Reference to allowed message definition response */
        #[NotBlank]
        public ?FHIRCanonical $message = null,
        /** @var FHIRMarkdown|null situation When should this response be used */
        public ?FHIRMarkdown $situation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
