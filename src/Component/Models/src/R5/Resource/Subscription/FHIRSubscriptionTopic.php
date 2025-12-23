<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/SubscriptionTopic
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
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url Canonical identifier for this subscription topic, represented as an absolute URI (globally unique) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business identifier for subscription topic */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Business version of the subscription topic */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding versionAlgorithmX How to compare versions */
		public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Name for this subscription topic (computer friendly) */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Name for this subscription topic (human friendly) */
		public FHIRString|string|null $title = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> derivedFrom Based on FHIR protocol or definition */
		public array $derivedFrom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean experimental If for testing purposes, not real usage */
		public ?FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime date Date status first applied */
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string publisher The name of the individual or organization that published the SubscriptionTopic */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Natural language description of the SubscriptionTopic */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> useContext Content intends to support these contexts */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction of the SubscriptionTopic (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown purpose Why this SubscriptionTopic is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string copyrightLabel Copyright holder and year(s) */
		public FHIRString|string|null $copyrightLabel = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate approvalDate When SubscriptionTopic is/was approved by publisher */
		public ?FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate lastReviewDate Date the Subscription Topic was last reviewed by the publisher */
		public ?FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod effectivePeriod The effective date range for the SubscriptionTopic */
		public ?FHIRPeriod $effectivePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionTopicResourceTrigger> resourceTrigger Definition of a resource-based trigger for the subscription topic */
		public array $resourceTrigger = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionTopicEventTrigger> eventTrigger Event definitions the SubscriptionTopic */
		public array $eventTrigger = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionTopicCanFilterBy> canFilterBy Properties by which a Subscription can filter notifications from the SubscriptionTopic */
		public array $canFilterBy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionTopicNotificationShape> notificationShape Properties for describing the shape of notifications generated by this topic */
		public array $notificationShape = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
