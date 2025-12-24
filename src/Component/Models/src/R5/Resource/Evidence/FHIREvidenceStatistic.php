<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;

/**
 * @description Values and parameters for a single statistic.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic', fhirVersion: 'R5')]
class FHIREvidenceStatistic extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description Description of content */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Footnotes and/or explanatory notes */
        public array $note = [],
        /** @var FHIRCodeableConcept|null statisticType Type of statistic, e.g., relative risk */
        public ?FHIRCodeableConcept $statisticType = null,
        /** @var FHIRCodeableConcept|null category Associated category for categorical variable */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRQuantity|null quantity Statistic value */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRUnsignedInt|null numberOfEvents The number of events associated with the statistic */
        public ?FHIRUnsignedInt $numberOfEvents = null,
        /** @var FHIRUnsignedInt|null numberAffected The number of participants affected */
        public ?FHIRUnsignedInt $numberAffected = null,
        /** @var FHIREvidenceStatisticSampleSize|null sampleSize Number of samples in the statistic */
        public ?FHIREvidenceStatisticSampleSize $sampleSize = null,
        /** @var array<FHIREvidenceStatisticAttributeEstimate> attributeEstimate An attribute of the Statistic */
        public array $attributeEstimate = [],
        /** @var array<FHIREvidenceStatisticModelCharacteristic> modelCharacteristic An aspect of the statistical model */
        public array $modelCharacteristic = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
