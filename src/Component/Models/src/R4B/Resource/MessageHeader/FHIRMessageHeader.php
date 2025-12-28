<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Infrastructure And Messaging)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MessageHeader
 *
 * @description The header for a message exchange that is either requesting or responding to an action.  The reference(s) that are the subject of the action as well as other information related to the action are typically transmitted in a bundle in which the MessageHeader resource instance is the first resource in the bundle.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MessageHeader',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MessageHeader',
    fhirVersion: 'R4B',
)]
class FHIRMessageHeader extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRCoding|FHIRUri|null eventX Code for the event this message represents or link to event definition */
        #[NotBlank]
        public \FHIRCoding|\FHIRUri|null $eventX = null,
        /** @var array<FHIRMessageHeaderDestination> destination Message destination application(s) */
        public array $destination = [],
        /** @var FHIRReference|null sender Real world sender of the message */
        public ?\FHIRReference $sender = null,
        /** @var FHIRReference|null enterer The source of the data entry */
        public ?\FHIRReference $enterer = null,
        /** @var FHIRReference|null author The source of the decision */
        public ?\FHIRReference $author = null,
        /** @var FHIRMessageHeaderSource|null source Message source application */
        #[NotBlank]
        public ?\FHIRMessageHeaderSource $source = null,
        /** @var FHIRReference|null responsible Final responsibility for event */
        public ?\FHIRReference $responsible = null,
        /** @var FHIRCodeableConcept|null reason Cause of event */
        public ?\FHIRCodeableConcept $reason = null,
        /** @var FHIRMessageHeaderResponse|null response If this is a reply to prior message */
        public ?\FHIRMessageHeaderResponse $response = null,
        /** @var array<FHIRReference> focus The actual content of the message */
        public array $focus = [],
        /** @var FHIRCanonical|null definition Link to the definition for this message */
        public ?\FHIRCanonical $definition = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
