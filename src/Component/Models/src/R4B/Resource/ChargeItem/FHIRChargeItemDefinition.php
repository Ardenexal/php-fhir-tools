<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/ChargeItemDefinition
 * @description The ChargeItemDefinition resource provides the properties that apply to the (billing) codes necessary to calculate costs and prices. The properties may differ largely depending on type and realm, therefore this resource gives only a rough structure and requires profiling for each type of billing code system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ChargeItemDefinition',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ChargeItemDefinition',
	fhirVersion: 'R4B',
)]
class FHIRChargeItemDefinition extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri url Canonical identifier for this charge item definition, represented as a URI (globally unique) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Additional identifier for the charge item definition */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string version Business version of the charge item definition */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string title Name for this charge item definition (human friendly) */
		public FHIRString|string|null $title = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri> derivedFromUri Underlying externally-defined charge item definition */
		public array $derivedFromUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical> partOf A larger definition of which this particular definition is a component or step */
		public array $partOf = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical> replaces Completed or terminated request(s) whose function is taken by this new request */
		public array $replaces = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean experimental For testing purposes, not real usage */
		public ?FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime date Date last changed */
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string publisher Name of the publisher (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description Natural language description of the charge item definition */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for charge item definition (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate approvalDate When the charge item definition was approved by publisher */
		public ?FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate lastReviewDate When the charge item definition was last reviewed */
		public ?FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod effectivePeriod When the charge item definition is expected to be used */
		public ?FHIRPeriod $effectivePeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept code Billing codes or product types this definition applies to */
		public ?FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> instance Instances this definition applies to */
		public array $instance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRChargeItemDefinitionApplicability> applicability Whether or not the billing code is applicable */
		public array $applicability = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRChargeItemDefinitionPropertyGroup> propertyGroup Group of properties which are applicable under the same conditions */
		public array $propertyGroup = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
