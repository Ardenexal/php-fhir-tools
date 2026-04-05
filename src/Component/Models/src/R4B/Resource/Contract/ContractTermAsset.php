<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Contract;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive;

/**
 * @description Contract Term Asset List.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset', fhirVersion: 'R4B')]
class ContractTermAsset extends BackboneElement
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
        /** @var CodeableConcept|null scope Range of asset */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $scope = null,
        /** @var array<CodeableConcept> type Asset category */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $type = [],
        /** @var array<Reference> typeReference Associated entities */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $typeReference = [],
        /** @var array<CodeableConcept> subtype Asset sub-category */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $subtype = [],
        /** @var Coding|null relationship Kinship of the asset */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $relationship = null,
        /** @var array<ContractTermAssetContext> context Circumstance of the asset */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Contract\ContractTermAssetContext',
        )]
        public array $context = [],
        /** @var StringPrimitive|string|null condition Quality desctiption of asset */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $condition = null,
        /** @var array<CodeableConcept> periodType Asset availability types */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $periodType = [],
        /** @var array<Period> period Time period of the asset */
        #[FhirProperty(
            fhirType: 'Period',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
        )]
        public array $period = [],
        /** @var array<Period> usePeriod Time period */
        #[FhirProperty(
            fhirType: 'Period',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
        )]
        public array $usePeriod = [],
        /** @var StringPrimitive|string|null text Asset clause or question text */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $text = null,
        /** @var array<StringPrimitive|string> linkId Pointer to asset text */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $linkId = [],
        /** @var array<ContractTermOfferAnswer> answer Response to assets */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Contract\ContractTermOfferAnswer',
        )]
        public array $answer = [],
        /** @var array<UnsignedIntPrimitive> securityLabelNumber Asset restriction numbers */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive', isArray: true)]
        public array $securityLabelNumber = [],
        /** @var array<ContractTermAssetValuedItem> valuedItem Contract Valued Item List */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Contract\ContractTermAssetValuedItem',
        )]
        public array $valuedItem = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
