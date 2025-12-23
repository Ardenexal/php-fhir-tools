<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Subscription
 * @description The subscription resource is used to define a push-based subscription from a server to another system. Once a subscription is registered with the server, the server checks every resource that is created or updated, and if the resource matches the given criteria, it sends a message on the defined "channel" so that another system can take an appropriate action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Subscription', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Subscription', fhirVersion: 'R4B')]
class FHIRSubscription extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubscriptionStatusCodesType status requested | active | error | off */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRSubscriptionStatusCodesType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactPoint> contact Contact details for source (e.g. troubleshooting) */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant end When to automatically delete the subscription */
		public ?FHIRInstant $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string reason Description of why this subscription was created */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRString|string|null $reason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string criteria Rule for server push */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRString|string|null $criteria = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string error Latest error note */
		public FHIRString|string|null $error = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubscriptionChannel channel The channel on which to report matches to the criteria */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRSubscriptionChannel $channel = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
