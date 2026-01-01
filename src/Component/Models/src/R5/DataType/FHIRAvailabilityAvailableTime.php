<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime;

/**
 * @description Times the {item} is available.
 */
#[FHIRComplexType(typeName: 'Availability.availableTime', fhirVersion: 'R5')]
class FHIRAvailabilityAvailableTime extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
        public array $daysOfWeek = [],
        /** @var FHIRBoolean|null allDay Always available? i.e. 24 hour service */
        public ?FHIRBoolean $allDay = null,
        /** @var FHIRTime|null availableStartTime Opening time of day (ignored if allDay = true) */
        public ?FHIRTime $availableStartTime = null,
        /** @var FHIRTime|null availableEndTime Closing time of day (ignored if allDay = true) */
        public ?FHIRTime $availableEndTime = null,
    ) {
        parent::__construct($id, $extension);
    }
}
