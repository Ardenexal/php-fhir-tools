<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description The root of the sections that make up the composition.
 */
#[FHIRBackboneElement(parentResource: 'EvidenceReport', elementPath: 'EvidenceReport.section', fhirVersion: 'R5')]
class FHIREvidenceReportSection extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null title Label for section (e.g. for ToC) */
        public FHIRString|string|null $title = null,
        /** @var FHIRCodeableConcept|null focus Classification of section (recommended) */
        public ?FHIRCodeableConcept $focus = null,
        /** @var FHIRReference|null focusReference Classification of section by Resource */
        public ?FHIRReference $focusReference = null,
        /** @var array<FHIRReference> author Who and/or what authored the section */
        public array $author = [],
        /** @var FHIRNarrative|null text Text summary of the section, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var FHIRListModeType|null mode working | snapshot | changes */
        public ?FHIRListModeType $mode = null,
        /** @var FHIRCodeableConcept|null orderedBy Order of section entries */
        public ?FHIRCodeableConcept $orderedBy = null,
        /** @var array<FHIRCodeableConcept> entryClassifier Extensible classifiers as content */
        public array $entryClassifier = [],
        /** @var array<FHIRReference> entryReference Reference to resources as content */
        public array $entryReference = [],
        /** @var array<FHIRQuantity> entryQuantity Quantity as content */
        public array $entryQuantity = [],
        /** @var FHIRCodeableConcept|null emptyReason Why the section is empty */
        public ?FHIRCodeableConcept $emptyReason = null,
        /** @var array<FHIREvidenceReportSection> section Nested Section */
        public array $section = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
