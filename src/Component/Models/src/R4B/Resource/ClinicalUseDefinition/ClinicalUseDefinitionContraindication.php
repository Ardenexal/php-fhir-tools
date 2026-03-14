<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\ClinicalUseDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

/**
 * @description Specifics for when this is a contraindication.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.contraindication', fhirVersion: 'R4B')]
class ClinicalUseDefinitionContraindication extends BackboneElement
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'          => 'http://hl7.org/fhirpath/System.String',
            'propertyKind'      => 'scalar',
            'isArray'           => false,
            'isRequired'        => false,
            'isChoice'          => false,
            'jsonKey'           => null,
            'phpType'           => null,
            'variants'          => null,
            'xmlSerializedName' => '@id',
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'diseaseSymptomProcedure' => [
            'fhirType'     => 'CodeableReference',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'diseaseStatus' => [
            'fhirType'     => 'CodeableReference',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'comorbidity' => [
            'fhirType'     => 'CodeableReference',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableReference',
            'variants'     => null,
        ],
        'indication' => [
            'fhirType'     => 'Reference',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
            'variants'     => null,
        ],
        'otherTherapy' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ClinicalUseDefinition\ClinicalUseDefinitionContraindicationOtherTherapy',
            'variants'     => null,
        ],
    ];

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
        /** @var CodeableReference|null diseaseSymptomProcedure The situation that is being documented as contraindicating against this item */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $diseaseSymptomProcedure = null,
        /** @var CodeableReference|null diseaseStatus The status of the disease or symptom for the contraindication */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $diseaseStatus = null,
        /** @var array<CodeableReference> comorbidity A comorbidity (concurrent condition) or coinfection */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isArray: true)]
        public array $comorbidity = [],
        /** @var array<Reference> indication The indication which this is a contraidication for */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
        public array $indication = [],
        /** @var array<ClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy Information about use of the product in relation to other therapies described as part of the contraindication */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $otherTherapy = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
