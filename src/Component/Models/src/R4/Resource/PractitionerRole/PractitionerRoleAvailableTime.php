<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
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
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'daysOfWeek' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'allDay' => [
            'fhirType'     => 'boolean',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'availableStartTime' => [
            'fhirType'     => 'time',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'availableEndTime' => [
            'fhirType'     => 'time',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
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
        /** @var bool|null allDay Always available? e.g. 24 hour service */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $allDay = null,
        /** @var TimePrimitive|null availableStartTime Opening time of day (ignored if allDay = true) */
        #[FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
        public ?TimePrimitive $availableStartTime = null,
        /** @var TimePrimitive|null availableEndTime Closing time of day (ignored if allDay = true) */
        #[FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
        public ?TimePrimitive $availableEndTime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
