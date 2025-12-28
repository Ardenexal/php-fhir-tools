<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/ArtifactAssessment
 * @description This Resource provides one or more comments, classifiers or ratings about a Resource and supports attribution and rights management metadata for the added content.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ArtifactAssessment',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ArtifactAssessment',
	fhirVersion: 'R5',
)]
class FHIRArtifactAssessment extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Additional identifier for the artifact assessment */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string title A short title for the assessment for use in displaying and selecting */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown citeAsX How to cite the comment or rating */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown|null $citeAsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime date Date last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate approvalDate When the artifact assessment was approved by publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate lastReviewDate When the artifact assessment was last reviewed by the publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri artifactX The artifact assessed, commented upon or rated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri|null $artifactX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRArtifactAssessmentContent> content Comment, classifier, or rating content */
		public array $content = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRArtifactAssessmentWorkflowStatusType workflowStatus submitted | triaged | waiting-for-input | resolved-no-change | resolved-change-required | deferred | duplicate | applied | published | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRArtifactAssessmentWorkflowStatusType $workflowStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRArtifactAssessmentDispositionType disposition unresolved | not-persuasive | persuasive | persuasive-with-modification | not-persuasive-with-modification */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRArtifactAssessmentDispositionType $disposition = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
