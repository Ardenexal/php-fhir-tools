<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description One or more Contract Provisions, which may be related and conveyed as a group, and may contain nested groups.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term', fhirVersion: 'R4')]
class ContractTerm extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Contract Term Number */
        public ?Identifier $identifier = null,
        /** @var DateTimePrimitive|null issued Contract Term Issue Date Time */
        public ?DateTimePrimitive $issued = null,
        /** @var Period|null applies Contract Term Effective Time */
        public ?Period $applies = null,
        /** @var CodeableConcept|Reference|null topicX Term Concern */
        public CodeableConcept|Reference|null $topicX = null,
        /** @var CodeableConcept|null type Contract Term Type or Form */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null subType Contract Term Type specific classification */
        public ?CodeableConcept $subType = null,
        /** @var StringPrimitive|string|null text Term Statement */
        public StringPrimitive|string|null $text = null,
        /** @var array<ContractTermSecurityLabel> securityLabel Protection for the Term */
        public array $securityLabel = [],
        /** @var ContractTermOffer|null offer Context of the Contract term */
        #[NotBlank]
        public ?ContractTermOffer $offer = null,
        /** @var array<ContractTermAsset> asset Contract Term Asset List */
        public array $asset = [],
        /** @var array<ContractTermAction> action Entity being ascribed responsibility */
        public array $action = [],
        /** @var array<ContractTerm> group Nested Contract Term Group */
        public array $group = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
