<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Location;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\DaysOfWeekType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive;

/**
 * @description What days/times during a week is this location usually open.
 */
#[FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.hoursOfOperation', fhirVersion: 'R4B')]
class LocationHoursOfOperation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<DaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $daysOfWeek = [],
        /** @var bool|null allDay The Location is open all day */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $allDay = null,
        /** @var TimePrimitive|null openingTime Time that the Location opens */
        #[FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
        public ?TimePrimitive $openingTime = null,
        /** @var TimePrimitive|null closingTime Time that the Location closes */
        #[FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
        public ?TimePrimitive $closingTime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
