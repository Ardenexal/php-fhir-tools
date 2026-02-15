<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking a role in an activity for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.action', fhirVersion: 'R4')]
class ContractTermAction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null doNotPerform True if the term prohibits the  action */
        public ?bool $doNotPerform = null,
        /** @var CodeableConcept|null type Type or form of the action */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var array<ContractTermActionSubject> subject Entity of the action */
        public array $subject = [],
        /** @var CodeableConcept|null intent Purpose for the Contract Term Action */
        #[NotBlank]
        public ?CodeableConcept $intent = null,
        /** @var array<StringPrimitive|string> linkId Pointer to specific item */
        public array $linkId = [],
        /** @var CodeableConcept|null status State of the action */
        #[NotBlank]
        public ?CodeableConcept $status = null,
        /** @var Reference|null context Episode associated with action */
        public ?Reference $context = null,
        /** @var array<StringPrimitive|string> contextLinkId Pointer to specific item */
        public array $contextLinkId = [],
        /** @var DateTimePrimitive|Period|Timing|null occurrenceX When action happens */
        public DateTimePrimitive|Period|Timing|null $occurrenceX = null,
        /** @var array<Reference> requester Who asked for action */
        public array $requester = [],
        /** @var array<StringPrimitive|string> requesterLinkId Pointer to specific item */
        public array $requesterLinkId = [],
        /** @var array<CodeableConcept> performerType Kind of service performer */
        public array $performerType = [],
        /** @var CodeableConcept|null performerRole Competency of the performer */
        public ?CodeableConcept $performerRole = null,
        /** @var Reference|null performer Actor that wil execute (or not) the action */
        public ?Reference $performer = null,
        /** @var array<StringPrimitive|string> performerLinkId Pointer to specific item */
        public array $performerLinkId = [],
        /** @var array<CodeableConcept> reasonCode Why is action (not) needed? */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why is action (not) needed? */
        public array $reasonReference = [],
        /** @var array<StringPrimitive|string> reason Why action is to be performed */
        public array $reason = [],
        /** @var array<StringPrimitive|string> reasonLinkId Pointer to specific item */
        public array $reasonLinkId = [],
        /** @var array<Annotation> note Comments about the action */
        public array $note = [],
        /** @var array<UnsignedIntPrimitive> securityLabelNumber Action restriction numbers */
        public array $securityLabelNumber = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
