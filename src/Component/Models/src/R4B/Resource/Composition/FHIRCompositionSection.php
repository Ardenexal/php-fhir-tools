<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The root of the sections that make up the composition.
 */
#[FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.section', fhirVersion: 'R4B')]
class FHIRCompositionSection extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null title Label for section (e.g. for ToC) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRCodeableConcept|null code Classification of section (recommended) */
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRReference> author Who and/or what authored the section */
        public array $author = [],
        /** @var FHIRReference|null focus Who/what the section is about, when it is not about the subject of composition */
        public ?\FHIRReference $focus = null,
        /** @var FHIRNarrative|null text Text summary of the section, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var FHIRListModeType|null mode working | snapshot | changes */
        public ?\FHIRListModeType $mode = null,
        /** @var FHIRCodeableConcept|null orderedBy Order of section entries */
        public ?\FHIRCodeableConcept $orderedBy = null,
        /** @var array<FHIRReference> entry A reference to data that supports this section */
        public array $entry = [],
        /** @var FHIRCodeableConcept|null emptyReason Why the section is empty */
        public ?\FHIRCodeableConcept $emptyReason = null,
        /** @var array<FHIRCompositionSection> section Nested Section */
        public array $section = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
