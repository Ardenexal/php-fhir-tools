<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Annotation
 *
 * @description A  text note which also  contains information about who made the statement and when.
 */
#[FHIRComplexType(typeName: 'Annotation', fhirVersion: 'R4')]
class FHIRAnnotation extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRReference|FHIRString|string|null authorX Individual responsible for the annotation */
        public \FHIRReference|\FHIRString|string|null $authorX = null,
        /** @var FHIRDateTime|null time When the annotation was made */
        public ?\FHIRDateTime $time = null,
        /** @var FHIRMarkdown|null text The annotation  - text content (as markdown) */
        #[NotBlank]
        public ?\FHIRMarkdown $text = null,
    ) {
        parent::__construct($id, $extension);
    }
}
