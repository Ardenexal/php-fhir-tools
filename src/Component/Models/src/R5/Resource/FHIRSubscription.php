<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Subscription
 * @description The subscription resource describes a particular client's request to be notified about a SubscriptionTopic.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Subscription', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Subscription', fhirVersion: 'R5')]
class FHIRSubscription extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Additional identifiers (business identifier) */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Human readable name for this subscription */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionStatusCodesType status requested | active | error | off | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRSubscriptionStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical topic Reference to the subscription topic being subscribed to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCanonical $topic = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint> contact Contact details for source (e.g. troubleshooting) */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant end When to automatically delete the subscription */
		public ?FHIRInstant $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference managingEntity Entity responsible for Subscription changes */
		public ?FHIRReference $managingEntity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string reason Description of why this subscription was created */
		public FHIRString|string|null $reason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionFilterBy> filterBy Criteria for narrowing the subscription topic stream */
		public array $filterBy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding channelType Channel type for notifications */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCoding $channelType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl endpoint Where the channel points to */
		public ?FHIRUrl $endpoint = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionParameter> parameter Channel type */
		public array $parameter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt heartbeatPeriod Interval in seconds to send 'heartbeat' notification */
		public ?FHIRUnsignedInt $heartbeatPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt timeout Timeout in seconds to attempt notification delivery */
		public ?FHIRUnsignedInt $timeout = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType contentType MIME type to send, or omit for no payload */
		public ?FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubscriptionPayloadContentType content empty | id-only | full-resource */
		public ?FHIRSubscriptionPayloadContentType $content = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt maxCount Maximum number of events that can be combined in a single notification */
		public ?FHIRPositiveInt $maxCount = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
