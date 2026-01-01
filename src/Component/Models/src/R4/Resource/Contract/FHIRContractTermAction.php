<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUnsignedInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking a role in an activity for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.action', fhirVersion: 'R4')]
class FHIRContractTermAction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null doNotPerform True if the term prohibits the  action */
        public ?FHIRBoolean $doNotPerform = null,
        /** @var FHIRCodeableConcept|null type Type or form of the action */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRContractTermActionSubject> subject Entity of the action */
        public array $subject = [],
        /** @var FHIRCodeableConcept|null intent Purpose for the Contract Term Action */
        #[NotBlank]
        public ?FHIRCodeableConcept $intent = null,
        /** @var array<FHIRString|string> linkId Pointer to specific item */
        public array $linkId = [],
        /** @var FHIRCodeableConcept|null status State of the action */
        #[NotBlank]
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRReference|null context Episode associated with action */
        public ?FHIRReference $context = null,
        /** @var array<FHIRString|string> contextLinkId Pointer to specific item */
        public array $contextLinkId = [],
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX When action happens */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
        /** @var array<FHIRReference> requester Who asked for action */
        public array $requester = [],
        /** @var array<FHIRString|string> requesterLinkId Pointer to specific item */
        public array $requesterLinkId = [],
        /** @var array<FHIRCodeableConcept> performerType Kind of service performer */
        public array $performerType = [],
        /** @var FHIRCodeableConcept|null performerRole Competency of the performer */
        public ?FHIRCodeableConcept $performerRole = null,
        /** @var FHIRReference|null performer Actor that wil execute (or not) the action */
        public ?FHIRReference $performer = null,
        /** @var array<FHIRString|string> performerLinkId Pointer to specific item */
        public array $performerLinkId = [],
        /** @var array<FHIRCodeableConcept> reasonCode Why is action (not) needed? */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why is action (not) needed? */
        public array $reasonReference = [],
        /** @var array<FHIRString|string> reason Why action is to be performed */
        public array $reason = [],
        /** @var array<FHIRString|string> reasonLinkId Pointer to specific item */
        public array $reasonLinkId = [],
        /** @var array<FHIRAnnotation> note Comments about the action */
        public array $note = [],
        /** @var array<FHIRUnsignedInt> securityLabelNumber Action restriction numbers */
        public array $securityLabelNumber = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
