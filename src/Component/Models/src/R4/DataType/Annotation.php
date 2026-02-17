<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Annotation
 *
 * @description A  text note which also  contains information about who made the statement and when.
 */
#[FHIRComplexType(typeName: 'Annotation', fhirVersion: 'R4')]
class Annotation extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var Reference|StringPrimitive|string|null authorX Individual responsible for the annotation */
        public Reference|StringPrimitive|string|null $authorX = null,
        /** @var DateTimePrimitive|null time When the annotation was made */
        public ?DateTimePrimitive $time = null,
        /** @var MarkdownPrimitive|null text The annotation  - text content (as markdown) */
        #[NotBlank]
        public ?MarkdownPrimitive $text = null,
    ) {
        parent::__construct($id, $extension);
    }
}
