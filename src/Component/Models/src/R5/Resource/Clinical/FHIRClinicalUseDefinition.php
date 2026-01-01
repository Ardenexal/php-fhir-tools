<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/ClinicalUseDefinition
 * @description A single issue - either an indication, contraindication, interaction or an undesirable effect for a medicinal product, medication, device or procedure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ClinicalUseDefinition',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ClinicalUseDefinition',
	fhirVersion: 'R5',
)]
class FHIRClinicalUseDefinition extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Business identifier for this issue */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRClinicalUseDefinitionTypeType type indication | contraindication | interaction | undesirable-effect | warning */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRClinicalUseDefinitionTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> category A categorisation of the issue, primarily for dividing warnings into subject heading areas such as "Pregnancy", "Overdose" */
		public array $category = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> subject The medication, product, substance, device, procedure etc. for which this is an indication */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept status Whether this is a current issue or one that has been retired etc */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClinicalUseDefinitionContraindication contraindication Specifics for when this is a contraindication */
		public ?FHIRClinicalUseDefinitionContraindication $contraindication = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClinicalUseDefinitionIndication indication Specifics for when this is an indication */
		public ?FHIRClinicalUseDefinitionIndication $indication = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClinicalUseDefinitionInteraction interaction Specifics for when this is an interaction */
		public ?FHIRClinicalUseDefinitionInteraction $interaction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> population The population group to which this applies */
		public array $population = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> library Logic used by the clinical use definition */
		public array $library = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClinicalUseDefinitionUndesirableEffect undesirableEffect A possible negative outcome from the use of this treatment */
		public ?FHIRClinicalUseDefinitionUndesirableEffect $undesirableEffect = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClinicalUseDefinitionWarning warning Critical environmental, health or physical risks or hazards. For example 'Do not operate heavy machinery', 'May cause drowsiness' */
		public ?FHIRClinicalUseDefinitionWarning $warning = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
