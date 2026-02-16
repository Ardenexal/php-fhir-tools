<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description The matter of concern in the context of this provision of the agrement.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.offer', fhirVersion: 'R4')]
class ContractTermOffer extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Offer business ID */
        public array $identifier = [],
        /** @var array<ContractTermOfferParty> party Offer Recipient */
        public array $party = [],
        /** @var Reference|null topic Negotiable offer asset */
        public ?Reference $topic = null,
        /** @var CodeableConcept|null type Contract Offer Type or Form */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null decision Accepting party choice */
        public ?CodeableConcept $decision = null,
        /** @var array<CodeableConcept> decisionMode How decision is conveyed */
        public array $decisionMode = [],
        /** @var array<ContractTermOfferAnswer> answer Response to offer text */
        public array $answer = [],
        /** @var StringPrimitive|string|null text Human readable offer text */
        public StringPrimitive|string|null $text = null,
        /** @var array<StringPrimitive|string> linkId Pointer to text */
        public array $linkId = [],
        /** @var array<UnsignedIntPrimitive> securityLabelNumber Offer restriction numbers */
        public array $securityLabelNumber = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
