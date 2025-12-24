<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDaysOfWeekType;

/**
 * @description What days/times during a week is this location usually open.
 */
#[FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.hoursOfOperation', fhirVersion: 'R4B')]
class FHIRLocationHoursOfOperation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
        public array $daysOfWeek = [],
        /** @var FHIRBoolean|null allDay The Location is open all day */
        public ?FHIRBoolean $allDay = null,
        /** @var FHIRTime|null openingTime Time that the Location opens */
        public ?FHIRTime $openingTime = null,
        /** @var FHIRTime|null closingTime Time that the Location closes */
        public ?FHIRTime $closingTime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
