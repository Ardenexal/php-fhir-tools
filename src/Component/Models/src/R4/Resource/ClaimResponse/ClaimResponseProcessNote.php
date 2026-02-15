<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\NoteTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A note that describes or explains adjudication results in a human readable form.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.processNote', fhirVersion: 'R4')]
class ClaimResponseProcessNote extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null number Note instance identifier */
        public ?PositiveIntPrimitive $number = null,
        /** @var NoteTypeType|null type display | print | printoper */
        public ?NoteTypeType $type = null,
        /** @var StringPrimitive|string|null text Note explanatory text */
        #[NotBlank]
        public StringPrimitive|string|null $text = null,
        /** @var CodeableConcept|null language Language of the text */
        public ?CodeableConcept $language = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
