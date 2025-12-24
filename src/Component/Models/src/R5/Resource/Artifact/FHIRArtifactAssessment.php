<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ArtifactAssessment
 *
 * @description This Resource provides one or more comments, classifiers or ratings about a Resource and supports attribution and rights management metadata for the added content.
 */
#[FhirResource(
    type: 'ArtifactAssessment',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ArtifactAssessment',
    fhirVersion: 'R5',
)]
class FHIRArtifactAssessment extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Additional identifier for the artifact assessment */
        public array $identifier = [],
        /** @var FHIRString|string|null title A short title for the assessment for use in displaying and selecting */
        public FHIRString|string|null $title = null,
        /** @var FHIRReference|FHIRMarkdown|null citeAsX How to cite the comment or rating */
        public FHIRReference|FHIRMarkdown|null $citeAsX = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRDate|null approvalDate When the artifact assessment was approved by publisher */
        public ?FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the artifact assessment was last reviewed by the publisher */
        public ?FHIRDate $lastReviewDate = null,
        /** @var FHIRReference|FHIRCanonical|FHIRUri|null artifactX The artifact assessed, commented upon or rated */
        #[NotBlank]
        public FHIRReference|FHIRCanonical|FHIRUri|null $artifactX = null,
        /** @var array<FHIRArtifactAssessmentContent> content Comment, classifier, or rating content */
        public array $content = [],
        /** @var FHIRArtifactAssessmentWorkflowStatusType|null workflowStatus submitted | triaged | waiting-for-input | resolved-no-change | resolved-change-required | deferred | duplicate | applied | published | entered-in-error */
        public ?FHIRArtifactAssessmentWorkflowStatusType $workflowStatus = null,
        /** @var FHIRArtifactAssessmentDispositionType|null disposition unresolved | not-persuasive | persuasive | persuasive-with-modification | not-persuasive-with-modification */
        public ?FHIRArtifactAssessmentDispositionType $disposition = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
