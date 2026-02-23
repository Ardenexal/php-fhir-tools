<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Characteristics of the patient that are relevant to the administration guidelines (for example, height, weight, gender, etc.).
 */
#[FHIRBackboneElement(
    parentResource: 'MedicationKnowledge',
    elementPath: 'MedicationKnowledge.administrationGuidelines.patientCharacteristics',
    fhirVersion: 'R4B',
)]
class MedicationKnowledgeAdministrationGuidelinesPatientCharacteristics extends BackboneElement
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
        'characteristicX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
                    'jsonKey'      => 'characteristicCodeableConcept',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
                    'jsonKey'      => 'characteristicQuantity',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'value' => [
            'fhirType'     => 'string',
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
        /** @var CodeableConcept|Quantity|null characteristicX Specific characteristic that is relevant to the administration guideline */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isRequired: true,
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
                    'jsonKey'      => 'characteristicCodeableConcept',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
                    'jsonKey'      => 'characteristicQuantity',
                ],
            ],
        )]
        #[NotBlank]
        public CodeableConcept|Quantity|null $characteristicX = null,
        /** @var array<StringPrimitive|string> value The specific characteristic */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $value = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
