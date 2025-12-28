<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description One or more Contract Provisions, which may be related and conveyed as a group, and may contain nested groups.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term', fhirVersion: 'R4B')]
class FHIRContractTerm extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Contract Term Number */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRDateTime|null issued Contract Term Issue Date Time */
        public ?\FHIRDateTime $issued = null,
        /** @var FHIRPeriod|null applies Contract Term Effective Time */
        public ?\FHIRPeriod $applies = null,
        /** @var FHIRCodeableConcept|FHIRReference|null topicX Term Concern */
        public \FHIRCodeableConcept|\FHIRReference|null $topicX = null,
        /** @var FHIRCodeableConcept|null type Contract Term Type or Form */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null subType Contract Term Type specific classification */
        public ?\FHIRCodeableConcept $subType = null,
        /** @var FHIRString|string|null text Term Statement */
        public \FHIRString|string|null $text = null,
        /** @var array<FHIRContractTermSecurityLabel> securityLabel Protection for the Term */
        public array $securityLabel = [],
        /** @var FHIRContractTermOffer|null offer Context of the Contract term */
        #[NotBlank]
        public ?\FHIRContractTermOffer $offer = null,
        /** @var array<FHIRContractTermAsset> asset Contract Term Asset List */
        public array $asset = [],
        /** @var array<FHIRContractTermAction> action Entity being ascribed responsibility */
        public array $action = [],
        /** @var array<FHIRContractTerm> group Nested Contract Term Group */
        public array $group = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
