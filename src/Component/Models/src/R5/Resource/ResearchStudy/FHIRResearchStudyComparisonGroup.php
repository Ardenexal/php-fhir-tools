<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Describes an expected event or sequence of events for one of the subjects of a study. E.g. for a living subject: exposure to drug A, wash-out, exposure to drug B, wash-out, follow-up. E.g. for a stability study: {store sample from lot A at 25 degrees for 1 month}, {store sample from lot A at 40 degrees for 1 month}.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.comparisonGroup', fhirVersion: 'R5')]
class FHIRResearchStudyComparisonGroup extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null linkId Allows the comparisonGroup for the study and the comparisonGroup for the subject to be linked easily */
        public ?FHIRId $linkId = null,
        /** @var FHIRString|string|null name Label for study comparisonGroup */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null type Categorization of study comparisonGroup */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRMarkdown|null description Short explanation of study path */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRReference> intendedExposure Interventions or exposures in this comparisonGroup or cohort */
        public array $intendedExposure = [],
        /** @var FHIRReference|null observedGroup Group of participants who were enrolled in study comparisonGroup */
        public ?FHIRReference $observedGroup = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
