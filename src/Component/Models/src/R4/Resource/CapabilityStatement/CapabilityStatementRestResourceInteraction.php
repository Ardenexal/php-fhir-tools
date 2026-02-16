<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TypeRestfulInteractionType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a restful operation supported by the solution.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource.interaction', fhirVersion: 'R4')]
class CapabilityStatementRestResourceInteraction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var TypeRestfulInteractionType|null code read | vread | update | patch | delete | history-instance | history-type | create | search-type */
        #[NotBlank]
        public ?TypeRestfulInteractionType $code = null,
        /** @var MarkdownPrimitive|null documentation Anything special about operation behavior */
        public ?MarkdownPrimitive $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
