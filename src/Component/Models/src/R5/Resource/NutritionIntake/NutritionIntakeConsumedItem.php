<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\NutritionIntake;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Timing;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description What food or fluid product or item was consumed.
 */
#[FHIRBackboneElement(parentResource: 'NutritionIntake', elementPath: 'NutritionIntake.consumedItem', fhirVersion: 'R5')]
class NutritionIntakeConsumedItem extends BackboneElement
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
        /** @var CodeableConcept|null type The type of food or fluid product */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableReference|null nutritionProduct Code that identifies the food or fluid product that was consumed */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableReference $nutritionProduct = null,
        /** @var Timing|null schedule Scheduled frequency of consumption */
        #[FhirProperty(fhirType: 'Timing', propertyKind: 'complex')]
        public ?Timing $schedule = null,
        /** @var Quantity|null amount Quantity of the specified food */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $amount = null,
        /** @var Quantity|null rate Rate at which enteral feeding was administered */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $rate = null,
        /** @var bool|null notConsumed Flag to indicate if the food or fluid item was refused or otherwise not consumed */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $notConsumed = null,
        /** @var CodeableConcept|null notConsumedReason Reason food or fluid was not consumed */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $notConsumedReason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
