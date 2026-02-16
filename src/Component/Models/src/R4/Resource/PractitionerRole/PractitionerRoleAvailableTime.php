<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DaysOfWeekType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;

/**
 * @description A collection of times the practitioner is available or performing this role at the location and/or healthcareservice.
 */
#[FHIRBackboneElement(parentResource: 'PractitionerRole', elementPath: 'PractitionerRole.availableTime', fhirVersion: 'R4')]
class PractitionerRoleAvailableTime extends BackboneElement
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
        /** @var bool|null allDay Always available? e.g. 24 hour service */
        public ?bool $allDay = null,
        /** @var TimePrimitive|null availableStartTime Opening time of day (ignored if allDay = true) */
        public ?TimePrimitive $availableStartTime = null,
        /** @var TimePrimitive|null availableEndTime Closing time of day (ignored if allDay = true) */
        public ?TimePrimitive $availableEndTime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
