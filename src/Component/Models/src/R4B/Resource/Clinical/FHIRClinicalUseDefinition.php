<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/ClinicalUseDefinition
 * @description A single issue - either an indication, contraindication, interaction or an undesirable effect for a medicinal product, medication, device or procedure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ClinicalUseDefinition',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ClinicalUseDefinition',
	fhirVersion: 'R4B',
)]
class FHIRClinicalUseDefinition extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Business identifier for this issue */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionTypeType type indication | contraindication | interaction | undesirable-effect | warning */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRClinicalUseDefinitionTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> category A categorisation of the issue, primarily for dividing warnings into subject heading areas such as "Pregnancy", "Overdose" */
		public array $category = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> subject The medication or procedure for which this is an indication */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept status Whether this is a current issue or one that has been retired etc */
		public ?FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionContraindication contraindication Specifics for when this is a contraindication */
		public ?FHIRClinicalUseDefinitionContraindication $contraindication = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionIndication indication Specifics for when this is an indication */
		public ?FHIRClinicalUseDefinitionIndication $indication = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionInteraction interaction Specifics for when this is an interaction */
		public ?FHIRClinicalUseDefinitionInteraction $interaction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> population The population group to which this applies */
		public array $population = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionUndesirableEffect undesirableEffect A possible negative outcome from the use of this treatment */
		public ?FHIRClinicalUseDefinitionUndesirableEffect $undesirableEffect = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionWarning warning Critical environmental, health or physical risks or hazards. For example 'Do not operate heavy machinery', 'May cause drowsiness' */
		public ?FHIRClinicalUseDefinitionWarning $warning = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
