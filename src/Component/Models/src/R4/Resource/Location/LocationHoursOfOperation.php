<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Location;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DaysOfWeekType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;

/**
 * @description What days/times during a week is this location usually open.
 */
#[FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.hoursOfOperation', fhirVersion: 'R4')]
class LocationHoursOfOperation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<DaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
        public array $daysOfWeek = [],
        /** @var bool|null allDay The Location is open all day */
        public ?bool $allDay = null,
        /** @var TimePrimitive|null openingTime Time that the Location opens */
        public ?TimePrimitive $openingTime = null,
        /** @var TimePrimitive|null closingTime Time that the Location closes */
        public ?TimePrimitive $closingTime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
