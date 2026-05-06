<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;

/**
 * @description Information about weekly recurring appointments.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate.weeklyTemplate', fhirVersion: 'R5')]
class AppointmentRecurrenceTemplateWeeklyTemplate extends BackboneElement
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
        /** @var bool|null monday Recurs on Mondays */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $monday = null,
        /** @var bool|null tuesday Recurs on Tuesday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $tuesday = null,
        /** @var bool|null wednesday Recurs on Wednesday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $wednesday = null,
        /** @var bool|null thursday Recurs on Thursday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $thursday = null,
        /** @var bool|null friday Recurs on Friday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $friday = null,
        /** @var bool|null saturday Recurs on Saturday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $saturday = null,
        /** @var bool|null sunday Recurs on Sunday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $sunday = null,
        /** @var PositiveIntPrimitive|null weekInterval Recurs every nth week */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $weekInterval = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
