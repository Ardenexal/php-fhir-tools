<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description Contract Term Asset List.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset', fhirVersion: 'R4')]
class ContractTermAsset extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null scope Range of asset */
        public ?CodeableConcept $scope = null,
        /** @var array<CodeableConcept> type Asset category */
        public array $type = [],
        /** @var array<Reference> typeReference Associated entities */
        public array $typeReference = [],
        /** @var array<CodeableConcept> subtype Asset sub-category */
        public array $subtype = [],
        /** @var Coding|null relationship Kinship of the asset */
        public ?Coding $relationship = null,
        /** @var array<ContractTermAssetContext> context Circumstance of the asset */
        public array $context = [],
        /** @var StringPrimitive|string|null condition Quality desctiption of asset */
        public StringPrimitive|string|null $condition = null,
        /** @var array<CodeableConcept> periodType Asset availability types */
        public array $periodType = [],
        /** @var array<Period> period Time period of the asset */
        public array $period = [],
        /** @var array<Period> usePeriod Time period */
        public array $usePeriod = [],
        /** @var StringPrimitive|string|null text Asset clause or question text */
        public StringPrimitive|string|null $text = null,
        /** @var array<StringPrimitive|string> linkId Pointer to asset text */
        public array $linkId = [],
        /** @var array<ContractTermOfferAnswer> answer Response to assets */
        public array $answer = [],
        /** @var array<UnsignedIntPrimitive> securityLabelNumber Asset restriction numbers */
        public array $securityLabelNumber = [],
        /** @var array<ContractTermAssetValuedItem> valuedItem Contract Valued Item List */
        public array $valuedItem = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
