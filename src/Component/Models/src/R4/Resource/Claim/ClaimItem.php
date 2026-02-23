<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A claim line. Either a simple  product or service or a 'group' of details which can each be a simple items or groups of sub-details.
 */
#[FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.item', fhirVersion: 'R4')]
class ClaimItem extends BackboneElement
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
        'sequence' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'careTeamSequence' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'diagnosisSequence' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'procedureSequence' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'informationSequence' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'revenue' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'category' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'productOrService' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'modifier' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'programCode' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'servicedX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive',
                    'jsonKey'      => 'servicedDate',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'servicedPeriod',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'locationX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'locationCodeableConcept',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Address',
                    'jsonKey'      => 'locationAddress',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
                    'jsonKey'      => 'locationReference',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'quantity' => [
            'fhirType'     => 'Quantity',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'unitPrice' => [
            'fhirType'     => 'Money',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'factor' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'net' => [
            'fhirType'     => 'Money',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'udi' => [
            'fhirType'     => 'Reference',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'bodySite' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'subSite' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'encounter' => [
            'fhirType'     => 'Reference',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'detail' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
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
        /** @var PositiveIntPrimitive|null sequence Item instance identifier */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
        /** @var array<PositiveIntPrimitive> careTeamSequence Applicable careTeam members */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $careTeamSequence = [],
        /** @var array<PositiveIntPrimitive> diagnosisSequence Applicable diagnoses */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $diagnosisSequence = [],
        /** @var array<PositiveIntPrimitive> procedureSequence Applicable procedures */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $procedureSequence = [],
        /** @var array<PositiveIntPrimitive> informationSequence Applicable exception and supporting information */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $informationSequence = [],
        /** @var CodeableConcept|null revenue Revenue or cost center code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $revenue = null,
        /** @var CodeableConcept|null category Benefit classification */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $productOrService = null,
        /** @var array<CodeableConcept> modifier Product or service billing modifiers */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $modifier = [],
        /** @var array<CodeableConcept> programCode Program the product or service is provided under */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $programCode = [],
        /** @var DatePrimitive|Period|null servicedX Date or dates of service or product delivery */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive',
                    'jsonKey'      => 'servicedDate',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'servicedPeriod',
                ],
            ],
        )]
        public DatePrimitive|Period|null $servicedX = null,
        /** @var CodeableConcept|Address|Reference|null locationX Place of service or where product was supplied */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'locationCodeableConcept',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Address',
                    'jsonKey'      => 'locationAddress',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
                    'jsonKey'      => 'locationReference',
                ],
            ],
        )]
        public CodeableConcept|Address|Reference|null $locationX = null,
        /** @var Quantity|null quantity Count of products or services */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var Money|null unitPrice Fee, charge or cost per item */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $unitPrice = null,
        /** @var float|null factor Price scaling factor */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $factor = null,
        /** @var Money|null net Total item cost */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $net = null,
        /** @var array<Reference> udi Unique device identifier */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
        public array $udi = [],
        /** @var CodeableConcept|null bodySite Anatomical location */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $bodySite = null,
        /** @var array<CodeableConcept> subSite Anatomical sub-location */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $subSite = [],
        /** @var array<Reference> encounter Encounters related to this billed item */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
        public array $encounter = [],
        /** @var array<ClaimItemDetail> detail Product or service provided */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
