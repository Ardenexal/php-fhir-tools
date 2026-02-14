<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DocumentModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A document definition.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.document', fhirVersion: 'R4')]
class CapabilityStatementDocument extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var DocumentModeType|null mode producer | consumer */
        #[NotBlank]
        public ?DocumentModeType $mode = null,
        /** @var MarkdownPrimitive|null documentation Description of document support */
        public ?MarkdownPrimitive $documentation = null,
        /** @var CanonicalPrimitive|null profile Constraint on the resources used in the document */
        #[NotBlank]
        public ?CanonicalPrimitive $profile = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
