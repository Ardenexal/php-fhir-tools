<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EncounterLocationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of locations where  the patient has been during this encounter.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.location', fhirVersion: 'R4')]
class EncounterLocation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null location Location the encounter takes place */
        #[NotBlank]
        public ?Reference $location = null,
        /** @var EncounterLocationStatusType|null status planned | active | reserved | completed */
        public ?EncounterLocationStatusType $status = null,
        /** @var CodeableConcept|null physicalType The physical type of the location (usually the level in the location hierachy - bed room ward etc.) */
        public ?CodeableConcept $physicalType = null,
        /** @var Period|null period Time period during which the patient was present at the location */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
