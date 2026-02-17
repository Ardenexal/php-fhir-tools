<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GoalLifecycleStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Goal\GoalTarget;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Goal
 *
 * @description Describes the intended objective(s) for a patient, group or organization care, for example, weight loss, restoring an activity of daily living, obtaining herd immunity via immunization, meeting a process improvement objective, etc.
 */
#[FhirResource(type: 'Goal', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Goal', fhirVersion: 'R4')]
class GoalResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier External Ids for this goal */
        public array $identifier = [],
        /** @var GoalLifecycleStatusType|null lifecycleStatus proposed | planned | accepted | active | on-hold | completed | cancelled | entered-in-error | rejected */
        #[NotBlank]
        public ?GoalLifecycleStatusType $lifecycleStatus = null,
        /** @var CodeableConcept|null achievementStatus in-progress | improving | worsening | no-change | achieved | sustaining | not-achieved | no-progress | not-attainable */
        public ?CodeableConcept $achievementStatus = null,
        /** @var array<CodeableConcept> category E.g. Treatment, dietary, behavioral, etc. */
        public array $category = [],
        /** @var CodeableConcept|null priority high-priority | medium-priority | low-priority */
        public ?CodeableConcept $priority = null,
        /** @var CodeableConcept|null description Code or text describing goal */
        #[NotBlank]
        public ?CodeableConcept $description = null,
        /** @var Reference|null subject Who this goal is intended for */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var DatePrimitive|CodeableConcept|null startX When goal pursuit begins */
        public DatePrimitive|CodeableConcept|null $startX = null,
        /** @var array<GoalTarget> target Target outcome for the goal */
        public array $target = [],
        /** @var DatePrimitive|null statusDate When goal status took effect */
        public ?DatePrimitive $statusDate = null,
        /** @var StringPrimitive|string|null statusReason Reason for current status */
        public StringPrimitive|string|null $statusReason = null,
        /** @var Reference|null expressedBy Who's responsible for creating Goal? */
        public ?Reference $expressedBy = null,
        /** @var array<Reference> addresses Issues addressed by this goal */
        public array $addresses = [],
        /** @var array<Annotation> note Comments about the goal */
        public array $note = [],
        /** @var array<CodeableConcept> outcomeCode What result was achieved regarding the goal? */
        public array $outcomeCode = [],
        /** @var array<Reference> outcomeReference Observation that resulted from goal */
        public array $outcomeReference = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
