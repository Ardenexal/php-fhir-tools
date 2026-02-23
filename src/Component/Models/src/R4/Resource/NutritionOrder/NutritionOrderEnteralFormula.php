<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Feeding provided through the gastrointestinal tract via a tube, catheter, or stoma that delivers nutrition distal to the oral cavity.
 */
#[FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula', fhirVersion: 'R4')]
class NutritionOrderEnteralFormula extends BackboneElement
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
        'baseFormulaType' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'baseFormulaProductName' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'additiveType' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'additiveProductName' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'caloricDensity' => [
            'fhirType'     => 'Quantity',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'routeofAdministration' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'administration' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'maxVolumeToDeliver' => [
            'fhirType'     => 'Quantity',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'administrationInstruction' => [
            'fhirType'     => 'string',
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
        /** @var CodeableConcept|null baseFormulaType Type of enteral or infant formula */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $baseFormulaType = null,
        /** @var StringPrimitive|string|null baseFormulaProductName Product or brand name of the enteral or infant formula */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $baseFormulaProductName = null,
        /** @var CodeableConcept|null additiveType Type of modular component to add to the feeding */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $additiveType = null,
        /** @var StringPrimitive|string|null additiveProductName Product or brand name of the modular additive */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $additiveProductName = null,
        /** @var Quantity|null caloricDensity Amount of energy per specified volume that is required */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $caloricDensity = null,
        /** @var CodeableConcept|null routeofAdministration How the formula should enter the patient's gastrointestinal tract */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $routeofAdministration = null,
        /** @var array<NutritionOrderEnteralFormulaAdministration> administration Formula feeding instruction as structured data */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $administration = [],
        /** @var Quantity|null maxVolumeToDeliver Upper limit on formula volume per unit of time */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $maxVolumeToDeliver = null,
        /** @var StringPrimitive|string|null administrationInstruction Formula feeding instructions expressed as text */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $administrationInstruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
