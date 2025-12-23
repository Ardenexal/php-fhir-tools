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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Additional identifier for the artifact assessment */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title A short title for the assessment for use in displaying and selecting */
		public FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown citeAsX How to cite the comment or rating */
		public FHIRReference|FHIRMarkdown|null $citeAsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime date Date last changed */
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate approvalDate When the artifact assessment was approved by publisher */
		public ?FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate lastReviewDate When the artifact assessment was last reviewed by the publisher */
		public ?FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri artifactX The artifact assessed, commented upon or rated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRReference|FHIRCanonical|FHIRUri|null $artifactX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRArtifactAssessmentContent> content Comment, classifier, or rating content */
		public array $content = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRArtifactAssessmentWorkflowStatusType workflowStatus submitted | triaged | waiting-for-input | resolved-no-change | resolved-change-required | deferred | duplicate | applied | published | entered-in-error */
		public ?FHIRArtifactAssessmentWorkflowStatusType $workflowStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRArtifactAssessmentDispositionType disposition unresolved | not-persuasive | persuasive | persuasive-with-modification | not-persuasive-with-modification */
		public ?FHIRArtifactAssessmentDispositionType $disposition = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
