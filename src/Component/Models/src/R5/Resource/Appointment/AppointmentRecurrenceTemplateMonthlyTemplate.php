<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about monthly recurring appointments.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate.monthlyTemplate', fhirVersion: 'R5')]
class AppointmentRecurrenceTemplateMonthlyTemplate extends BackboneElement
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
        /** @var PositiveIntPrimitive|null dayOfMonth Recurs on a specific day of the month */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $dayOfMonth = null,
        /** @var Coding|null nthWeekOfMonth Indicates which week of the month the appointment should occur */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/week-of-month|5.0.0', strength: 'required')]
        public ?Coding $nthWeekOfMonth = null,
        /** @var Coding|null dayOfWeek Indicates which day of the week the appointment should occur */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/days-of-week|5.0.0', strength: 'required')]
        public ?Coding $dayOfWeek = null,
        /** @var PositiveIntPrimitive|null monthInterval Recurs every nth month */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PositiveIntPrimitive $monthInterval = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
