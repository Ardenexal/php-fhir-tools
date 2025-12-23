<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/SubscriptionStatus
 * @description The SubscriptionStatus resource describes the state of a Subscription during notifications.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SubscriptionStatus',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/SubscriptionStatus',
	fhirVersion: 'R5',
)]
class FHIRSubscriptionStatus extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionStatusCodesType status requested | active | error | off | entered-in-error */
		public ?FHIRSubscriptionStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionNotificationTypeType type handshake | heartbeat | event-notification | query-status | query-event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRSubscriptionNotificationTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger64 eventsSinceSubscriptionStart Events since the Subscription was created */
		public ?FHIRInteger64 $eventsSinceSubscriptionStart = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionStatusNotificationEvent> notificationEvent Detailed information about any events relevant to this notification */
		public array $notificationEvent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subscription Reference to the Subscription responsible for this notification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $subscription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical topic Reference to the SubscriptionTopic this notification relates to */
		public ?FHIRCanonical $topic = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> error List of errors on the subscription */
		public array $error = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
