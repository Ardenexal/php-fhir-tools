<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;

/**
 * @description The matter of concern in the context of this provision of the agrement.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.offer', fhirVersion: 'R5')]
class FHIRContractTermOffer extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Offer business ID */
        public array $identifier = [],
        /** @var array<FHIRContractTermOfferParty> party Offer Recipient */
        public array $party = [],
        /** @var FHIRReference|null topic Negotiable offer asset */
        public ?FHIRReference $topic = null,
        /** @var FHIRCodeableConcept|null type Contract Offer Type or Form */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null decision Accepting party choice */
        public ?FHIRCodeableConcept $decision = null,
        /** @var array<FHIRCodeableConcept> decisionMode How decision is conveyed */
        public array $decisionMode = [],
        /** @var array<FHIRContractTermOfferAnswer> answer Response to offer text */
        public array $answer = [],
        /** @var FHIRString|string|null text Human readable offer text */
        public FHIRString|string|null $text = null,
        /** @var array<FHIRString|string> linkId Pointer to text */
        public array $linkId = [],
        /** @var array<FHIRUnsignedInt> securityLabelNumber Offer restriction numbers */
        public array $securityLabelNumber = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
