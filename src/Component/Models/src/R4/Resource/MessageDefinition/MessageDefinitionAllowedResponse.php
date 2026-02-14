<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates what types of messages may be sent as an application-level response to this message.
 */
#[FHIRBackboneElement(parentResource: 'MessageDefinition', elementPath: 'MessageDefinition.allowedResponse', fhirVersion: 'R4')]
class MessageDefinitionAllowedResponse extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CanonicalPrimitive|null message Reference to allowed message definition response */
        #[NotBlank]
        public ?CanonicalPrimitive $message = null,
        /** @var MarkdownPrimitive|null situation When should this response be used */
        public ?MarkdownPrimitive $situation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
