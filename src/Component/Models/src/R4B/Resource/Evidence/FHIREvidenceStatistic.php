<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt;

/**
 * @description Values and parameters for a single statistic.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic', fhirVersion: 'R4B')]
class FHIREvidenceStatistic extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Description of content */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRAnnotation> note Footnotes and/or explanatory notes */
        public array $note = [],
        /** @var FHIRCodeableConcept|null statisticType Type of statistic, eg relative risk */
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
