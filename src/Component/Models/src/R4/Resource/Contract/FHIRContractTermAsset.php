<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUnsignedInt;

/**
 * @description Contract Term Asset List.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset', fhirVersion: 'R4')]
class FHIRContractTermAsset extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null scope Range of asset */
        public ?FHIRCodeableConcept $scope = null,
        /** @var array<FHIRCodeableConcept> type Asset category */
        public array $type = [],
        /** @var array<FHIRReference> typeReference Associated entities */
        public array $typeReference = [],
        /** @var array<FHIRCodeableConcept> subtype Asset sub-category */
        public array $subtype = [],
        /** @var FHIRCoding|null relationship Kinship of the asset */
        public ?FHIRCoding $relationship = null,
        /** @var array<FHIRContractTermAssetContext> context Circumstance of the asset */
        public array $context = [],
        /** @var FHIRString|string|null condition Quality desctiption of asset */
        public FHIRString|string|null $condition = null,
        /** @var array<FHIRCodeableConcept> periodType Asset availability types */
        public array $periodType = [],
        /** @var array<FHIRPeriod> period Time period of the asset */
        public array $period = [],
        /** @var array<FHIRPeriod> usePeriod Time period */
        public array $usePeriod = [],
        /** @var FHIRString|string|null text Asset clause or question text */
        public FHIRString|string|null $text = null,
        /** @var array<FHIRString|string> linkId Pointer to asset text */
        public array $linkId = [],
        /** @var array<FHIRContractTermOfferAnswer> answer Response to assets */
        public array $answer = [],
        /** @var array<FHIRUnsignedInt> securityLabelNumber Asset restriction numbers */
        public array $securityLabelNumber = [],
        /** @var array<FHIRContractTermAssetValuedItem> valuedItem Contract Valued Item List */
        public array $valuedItem = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
