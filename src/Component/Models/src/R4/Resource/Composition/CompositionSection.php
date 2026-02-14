<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ListModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description The root of the sections that make up the composition.
 */
#[FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.section', fhirVersion: 'R4')]
class CompositionSection extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null title Label for section (e.g. for ToC) */
        public StringPrimitive|string|null $title = null,
        /** @var CodeableConcept|null code Classification of section (recommended) */
        public ?CodeableConcept $code = null,
        /** @var array<Reference> author Who and/or what authored the section */
        public array $author = [],
        /** @var Reference|null focus Who/what the section is about, when it is not about the subject of composition */
        public ?Reference $focus = null,
        /** @var Narrative|null text Text summary of the section, for human interpretation */
        public ?Narrative $text = null,
        /** @var ListModeType|null mode working | snapshot | changes */
        public ?ListModeType $mode = null,
        /** @var CodeableConcept|null orderedBy Order of section entries */
        public ?CodeableConcept $orderedBy = null,
        /** @var array<Reference> entry A reference to data that supports this section */
        public array $entry = [],
        /** @var CodeableConcept|null emptyReason Why the section is empty */
        public ?CodeableConcept $emptyReason = null,
        /** @var array<CompositionSection> section Nested Section */
        public array $section = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
