<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/RegulatedAuthorization
 * @description Regulatory approval, clearance or licencing related to a regulated product, treatment, facility or activity that is cited in a guidance, regulation, rule or legislative act. An example is Market Authorization relating to a Medicinal Product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'RegulatedAuthorization',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/RegulatedAuthorization',
	fhirVersion: 'R5',
)]
class FHIRRegulatedAuthorization extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business identifier for the authorization, typically assigned by the authorizing body */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> subject The product type, treatment, facility or activity that is being authorized */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Overall type of this authorization, for example drug marketing approval, orphan drug designation */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description General textual supporting information */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> region The territory in which the authorization has been granted */
		public array $region = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept status The status that is authorised e.g. approved. Intermediate states can be tracked with cases and applications */
		public ?FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime statusDate The date at which the current status was assigned */
		public ?FHIRDateTime $statusDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod validityPeriod The time period in which the regulatory approval etc. is in effect, e.g. a Marketing Authorization includes the date of authorization and/or expiration date */
		public ?FHIRPeriod $validityPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> indication Condition for which the use of the regulated product applies */
		public array $indication = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept intendedUse The intended use of the product, e.g. prevention, treatment */
		public ?FHIRCodeableConcept $intendedUse = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> basis The legal/regulatory framework or reasons under which this authorization is granted */
		public array $basis = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference holder The organization that has been granted this authorization, by the regulator */
		public ?FHIRReference $holder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference regulator The regulatory authority or authorizing body granting the authorization */
		public ?FHIRReference $regulator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> attachedDocument Additional information or supporting documentation about the authorization */
		public array $attachedDocument = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRegulatedAuthorizationCase case The case or regulatory procedure for granting or amending a regulated authorization. Note: This area is subject to ongoing review and the workgroup is seeking implementer feedback on its use (see link at bottom of page) */
		public ?FHIRRegulatedAuthorizationCase $case = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
