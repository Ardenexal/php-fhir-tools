<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse
 *
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
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Unique id for this set of answers */
        public ?\FHIRIdentifier $identifier = null,
        /** @var array<FHIRReference> basedOn Request fulfilled by this QuestionnaireResponse */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of this action */
        public array $partOf = [],
        /** @var FHIRCanonical|null questionnaire Form being answered */
        public ?\FHIRCanonical $questionnaire = null,
        /** @var FHIRQuestionnaireResponseStatusType|null status in-progress | completed | amended | entered-in-error | stopped */
        #[NotBlank]
        public ?\FHIRQuestionnaireResponseStatusType $status = null,
        /** @var FHIRReference|null subject The subject of the questions */
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter created as part of */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|null authored Date the answers were gathered */
        public ?\FHIRDateTime $authored = null,
        /** @var FHIRReference|null author Person who received and recorded the answers */
        public ?\FHIRReference $author = null,
        /** @var FHIRReference|null source The person who answered the questions */
        public ?\FHIRReference $source = null,
        /** @var array<FHIRQuestionnaireResponseItem> item Groups and questions */
        public array $item = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
