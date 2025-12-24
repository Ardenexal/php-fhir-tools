<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Describes a setting/value on the environment for the adequate storage of the medication and other substances.  Environment settings may involve temperature, humidity, or exposure to light.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicationKnowledge',
    elementPath: 'MedicationKnowledge.storageGuideline.environmentalSetting',
    fhirVersion: 'R5',
)]
class FHIRMedicationKnowledgeStorageGuidelineEnvironmentalSetting extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Categorization of the setting */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|FHIRRange|FHIRCodeableConcept|null valueX Value of the setting */
        #[NotBlank]
        public FHIRQuantity|FHIRRange|FHIRCodeableConcept|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
