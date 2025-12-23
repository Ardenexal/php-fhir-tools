<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Community Based Collaborative Care)
 * @see http://hl7.org/fhir/StructureDefinition/Consent
 * @description A record of a healthcare consumer’s  choices, which permits or denies identified recipient(s) or recipient role(s) to perform one or more actions within a given policy context, for specific purposes and periods of time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Consent', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Consent', fhirVersion: 'R4B')]
class FHIRConsent extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Identifier for this record (external references) */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRConsentStateType status draft | proposed | active | rejected | inactive | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRConsentStateType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept scope Which of the four areas this resource covers (extensible) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $scope = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> category Classification of the consent statement - for indexing/retrieval */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference patient Who the consent applies to */
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime dateTime When this Consent was created or indexed */
		public ?FHIRDateTime $dateTime = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> performer Who is agreeing to the policy and rules */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> organization Custodian of the consent */
		public array $organization = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference sourceX Source from which this consent is taken */
		public FHIRAttachment|FHIRReference|null $sourceX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRConsentPolicy> policy Policies covered by this consent */
		public array $policy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept policyRule Regulation that this consents to */
		public ?FHIRCodeableConcept $policyRule = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRConsentVerification> verification Consent Verified by patient or family */
		public array $verification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRConsentProvision provision Constraints to the base Consent.policyRule */
		public ?FHIRConsentProvision $provision = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
