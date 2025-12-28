<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubscriptionTopic
 *
 * @description Describes a stream of resource state changes or events and annotated with labels useful to filter projections from this topic.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'SubscriptionTopic',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SubscriptionTopic',
    fhirVersion: 'R5',
)]
class FHIRSubscriptionTopic extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this subscription topic, represented as an absolute URI (globally unique) */
        #[NotBlank]
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Business identifier for subscription topic */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the subscription topic */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this subscription topic (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this subscription topic (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var array<FHIRCanonical> derivedFrom Based on FHIR protocol or definition */
        public array $derivedFrom = [],
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental If for testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date status first applied */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher The name of the individual or organization that published the SubscriptionTopic */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the SubscriptionTopic */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext Content intends to support these contexts */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction of the SubscriptionTopic (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this SubscriptionTopic is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When SubscriptionTopic is/was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate Date the Subscription Topic was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod The effective date range for the SubscriptionTopic */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRSubscriptionTopicResourceTrigger> resourceTrigger Definition of a resource-based trigger for the subscription topic */
        public array $resourceTrigger = [],
        /** @var array<FHIRSubscriptionTopicEventTrigger> eventTrigger Event definitions the SubscriptionTopic */
        public array $eventTrigger = [],
        /** @var array<FHIRSubscriptionTopicCanFilterBy> canFilterBy Properties by which a Subscription can filter notifications from the SubscriptionTopic */
        public array $canFilterBy = [],
        /** @var array<FHIRSubscriptionTopicNotificationShape> notificationShape Properties for describing the shape of notifications generated by this topic */
        public array $notificationShape = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
