<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive;

/**
 * @description Times the {item} is available.
 */
#[FHIRComplexType(typeName: 'Availability.availableTime', fhirVersion: 'R5')]
class AvailabilityAvailableTime extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<DaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $daysOfWeek = [],
        /** @var bool|null allDay Always available? i.e. 24 hour service */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $allDay = null,
        /** @var TimePrimitive|null availableStartTime Opening time of day (ignored if allDay = true) */
        #[FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
        public ?TimePrimitive $availableStartTime = null,
        /** @var TimePrimitive|null availableEndTime Closing time of day (ignored if allDay = true) */
        #[FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
        public ?TimePrimitive $availableEndTime = null,
    ) {
        parent::__construct($id, $extension);
    }
}
