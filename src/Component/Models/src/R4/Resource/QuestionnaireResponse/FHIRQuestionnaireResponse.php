<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse
 * @description A structured set of questions and their answers. The questions are ordered and grouped into coherent subsets, corresponding to the structure of the grouping of the questionnaire being responded to.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'QuestionnaireResponse',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse',
	fhirVersion: 'R4',
)]
class FHIRQuestionnaireResponse extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier identifier Unique id for this set of answers */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier $identifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> basedOn Request fulfilled by this QuestionnaireResponse */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> partOf Part of this action */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical questionnaire Form being answered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical $questionnaire = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuestionnaireResponseStatusType status in-progress | completed | amended | entered-in-error | stopped */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuestionnaireResponseStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference subject The subject of the questions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference encounter Encounter created as part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime authored Date the answers were gathered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $authored = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference author Person who received and recorded the answers */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference source The person who answered the questions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $source = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireResponseItem> item Groups and questions */
		public array $item = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
