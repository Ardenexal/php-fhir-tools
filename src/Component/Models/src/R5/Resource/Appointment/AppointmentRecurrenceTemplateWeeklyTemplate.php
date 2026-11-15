<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\BooleanPrimitive;
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
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var BooleanPrimitive|bool|null monday Recurs on Mondays */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public BooleanPrimitive|bool|null $monday = null,
        /** @var BooleanPrimitive|bool|null tuesday Recurs on Tuesday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public BooleanPrimitive|bool|null $tuesday = null,
        /** @var BooleanPrimitive|bool|null wednesday Recurs on Wednesday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public BooleanPrimitive|bool|null $wednesday = null,
        /** @var BooleanPrimitive|bool|null thursday Recurs on Thursday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public BooleanPrimitive|bool|null $thursday = null,
        /** @var BooleanPrimitive|bool|null friday Recurs on Friday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public BooleanPrimitive|bool|null $friday = null,
        /** @var BooleanPrimitive|bool|null saturday Recurs on Saturday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public BooleanPrimitive|bool|null $saturday = null,
        /** @var BooleanPrimitive|bool|null sunday Recurs on Sunday */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public BooleanPrimitive|bool|null $sunday = null,
        /** @var PositiveIntPrimitive|null weekInterval Recurs every nth week */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $weekInterval = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
