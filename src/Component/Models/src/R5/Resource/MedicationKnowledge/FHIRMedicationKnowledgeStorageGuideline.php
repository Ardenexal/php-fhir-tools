<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description Information on how the medication should be stored, for example, refrigeration temperatures and length of stability at a given temperature.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.storageGuideline', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeStorageGuideline extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null reference Reference to additional information */
        public ?FHIRUri $reference = null,
        /** @var array<FHIRAnnotation> note Additional storage notes */
        public array $note = [],
        /** @var FHIRDuration|null stabilityDuration Duration remains stable */
        public ?FHIRDuration $stabilityDuration = null,
        /** @var array<FHIRMedicationKnowledgeStorageGuidelineEnvironmentalSetting> environmentalSetting Setting or value of environment for adequate storage */
        public array $environmentalSetting = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
