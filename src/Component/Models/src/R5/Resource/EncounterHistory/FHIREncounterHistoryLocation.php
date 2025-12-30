<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The location of the patient at this point in the encounter, the multiple cardinality permits de-normalizing the levels of the location hierarchy, such as site/ward/room/bed.
 */
#[FHIRBackboneElement(parentResource: 'EncounterHistory', elementPath: 'EncounterHistory.location', fhirVersion: 'R5')]
class FHIREncounterHistoryLocation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null location Location the encounter takes place */
        #[NotBlank]
        public ?FHIRReference $location = null,
        /** @var FHIRCodeableConcept|null form The physical type of the location (usually the level in the location hierarchy - bed, room, ward, virtual etc.) */
        public ?FHIRCodeableConcept $form = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
