<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The details of the recurrence pattern or template that is used to generate recurring appointments.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate', fhirVersion: 'R5')]
class AppointmentRecurrenceTemplate extends BackboneElement
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
        'timezone' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'recurrenceType' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'lastOccurrenceDate' => [
            'fhirType'     => 'date',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'occurrenceCount' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'occurrenceDate' => [
            'fhirType'     => 'date',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'weeklyTemplate' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'monthlyTemplate' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'yearlyTemplate' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'excludingDate' => [
            'fhirType'     => 'date',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'excludingRecurrenceId' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => true,
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
        /** @var CodeableConcept|null timezone The timezone of the occurrences */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $timezone = null,
        /** @var CodeableConcept|null recurrenceType The frequency of the recurrence */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $recurrenceType = null,
        /** @var DatePrimitive|null lastOccurrenceDate The date when the recurrence should end */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $lastOccurrenceDate = null,
        /** @var PositiveIntPrimitive|null occurrenceCount The number of planned occurrences */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $occurrenceCount = null,
        /** @var array<DatePrimitive> occurrenceDate Specific dates for a recurring set of appointments (no template) */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive', isArray: true)]
        public array $occurrenceDate = [],
        /** @var AppointmentRecurrenceTemplateWeeklyTemplate|null weeklyTemplate Information about weekly recurring appointments */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?AppointmentRecurrenceTemplateWeeklyTemplate $weeklyTemplate = null,
        /** @var AppointmentRecurrenceTemplateMonthlyTemplate|null monthlyTemplate Information about monthly recurring appointments */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?AppointmentRecurrenceTemplateMonthlyTemplate $monthlyTemplate = null,
        /** @var AppointmentRecurrenceTemplateYearlyTemplate|null yearlyTemplate Information about yearly recurring appointments */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?AppointmentRecurrenceTemplateYearlyTemplate $yearlyTemplate = null,
        /** @var array<DatePrimitive> excludingDate Any dates that should be excluded from the series */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive', isArray: true)]
        public array $excludingDate = [],
        /** @var array<PositiveIntPrimitive> excludingRecurrenceId Any recurrence IDs that should be excluded from the recurrence */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $excludingRecurrenceId = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
