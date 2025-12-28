<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Goal
 *
 * @description Describes the intended objective(s) for a patient, group or organization care, for example, weight loss, restoring an activity of daily living, obtaining herd immunity via immunization, meeting a process improvement objective, etc.
 */
#[FhirResource(type: 'Goal', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Goal', fhirVersion: 'R5')]
class FHIRGoal extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External Ids for this goal */
        public array $identifier = [],
        /** @var FHIRGoalLifecycleStatusType|null lifecycleStatus proposed | planned | accepted | active | on-hold | completed | cancelled | entered-in-error | rejected */
        #[NotBlank]
        public ?FHIRGoalLifecycleStatusType $lifecycleStatus = null,
        /** @var FHIRCodeableConcept|null achievementStatus in-progress | improving | worsening | no-change | achieved | sustaining | not-achieved | no-progress | not-attainable */
        public ?FHIRCodeableConcept $achievementStatus = null,
        /** @var array<FHIRCodeableConcept> category E.g. Treatment, dietary, behavioral, etc */
        public array $category = [],
        /** @var FHIRBoolean|null continuous After meeting the goal, ongoing activity is needed to sustain the goal objective */
        public ?FHIRBoolean $continuous = null,
        /** @var FHIRCodeableConcept|null priority high-priority | medium-priority | low-priority */
        public ?FHIRCodeableConcept $priority = null,
        /** @var FHIRCodeableConcept|null description Code or text describing goal */
        #[NotBlank]
        public ?FHIRCodeableConcept $description = null,
        /** @var FHIRReference|null subject Who this goal is intended for */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRDate|FHIRCodeableConcept|null startX When goal pursuit begins */
        public FHIRDate|FHIRCodeableConcept|null $startX = null,
        /** @var array<FHIRGoalTarget> target Target outcome for the goal */
        public array $target = [],
        /** @var FHIRDate|null statusDate When goal status took effect */
        public ?FHIRDate $statusDate = null,
        /** @var FHIRString|string|null statusReason Reason for current status */
        public FHIRString|string|null $statusReason = null,
        /** @var FHIRReference|null source Who's responsible for creating Goal? */
        public ?FHIRReference $source = null,
        /** @var array<FHIRReference> addresses Issues addressed by this goal */
        public array $addresses = [],
        /** @var array<FHIRAnnotation> note Comments about the goal */
        public array $note = [],
        /** @var array<FHIRCodeableReference> outcome What result was achieved regarding the goal? */
        public array $outcome = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
