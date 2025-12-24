<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of locations where  the patient has been during this encounter.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.location', fhirVersion: 'R5')]
class FHIREncounterLocation extends FHIRBackboneElement
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
        /** @var FHIREncounterLocationStatusType|null status planned | active | reserved | completed */
        public ?FHIREncounterLocationStatusType $status = null,
        /** @var FHIRCodeableConcept|null form The physical type of the location (usually the level in the location hierarchy - bed, room, ward, virtual etc.) */
        public ?FHIRCodeableConcept $form = null,
        /** @var FHIRPeriod|null period Time period during which the patient was present at the location */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
