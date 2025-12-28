<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime;

/**
 * @description A collection of times that the Service Site is available.
 */
#[FHIRBackboneElement(parentResource: 'HealthcareService', elementPath: 'HealthcareService.availableTime', fhirVersion: 'R4')]
class FHIRHealthcareServiceAvailableTime extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
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
        /** @var FHIRBoolean|null allDay Always available? e.g. 24 hour service */
        public ?FHIRBoolean $allDay = null,
        /** @var FHIRTime|null availableStartTime Opening time of day (ignored if allDay = true) */
        public ?FHIRTime $availableStartTime = null,
        /** @var FHIRTime|null availableEndTime Closing time of day (ignored if allDay = true) */
        public ?FHIRTime $availableEndTime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
