<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Community Based Collaborative Care)
 * @see http://hl7.org/fhir/StructureDefinition/Consent
 * @description A record of a healthcare consumer’s  choices  or choices made on their behalf by a third party, which permits or denies identified recipient(s) or recipient role(s) to perform one or more actions within a given policy context, for specific purposes and periods of time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Consent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Consent', fhirVersion: 'R5')]
class FHIRConsent extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Identifier for this record (external references) */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConsentStateType status draft | active | inactive | not-done | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConsentStateType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> category Classification of the consent statement - for indexing/retrieval */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference subject Who the consent applies to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate date Fully executed date of the consent */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod period Effective period for this Consent */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> grantor Who is granting rights according to the policy and rules */
		public array $grantor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> grantee Who is agreeing to the policy and rules */
		public array $grantee = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> manager Consent workflow management */
		public array $manager = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> controller Consent Enforcer */
		public array $controller = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment> sourceAttachment Source from which this consent is taken */
		public array $sourceAttachment = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> sourceReference Source from which this consent is taken */
		public array $sourceReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> regulatoryBasis Regulations establishing base Consent */
		public array $regulatoryBasis = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentPolicyBasis policyBasis Computable version of the backing policy */
		public ?FHIRConsentPolicyBasis $policyBasis = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> policyText Human Readable Policy */
		public array $policyText = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentVerification> verification Consent Verified by patient or family */
		public array $verification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConsentProvisionTypeType decision deny | permit */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConsentProvisionTypeType $decision = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentProvision> provision Constraints to the base Consent.policyRule/Consent.policy */
		public array $provision = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
