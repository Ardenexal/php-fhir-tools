<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCarePlanIntentType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CarePlan
 *
 * @description Describes the intention of how one or more practitioners intend to deliver care for a particular patient, group or community for a period of time, possibly limited to care for a specific condition or set of conditions.
 */
#[FhirResource(type: 'CarePlan', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/CarePlan', fhirVersion: 'R5')]
class FHIRCarePlan extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External Ids for this plan */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var array<FHIRReference> replaces CarePlan replaced by this CarePlan */
        public array $replaces = [],
        /** @var array<FHIRReference> partOf Part of referenced CarePlan */
        public array $partOf = [],
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRRequestStatusType $status = null,
        /** @var FHIRCarePlanIntentType|null intent proposal | plan | order | option | directive */
        #[NotBlank]
        public ?FHIRCarePlanIntentType $intent = null,
        /** @var array<FHIRCodeableConcept> category Type of plan */
        public array $category = [],
        /** @var FHIRString|string|null title Human-friendly name for the care plan */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null description Summary of nature of plan */
        public FHIRString|string|null $description = null,
        /** @var FHIRReference|null subject Who the care plan is for */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter The Encounter during which this CarePlan was created */
        public ?FHIRReference $encounter = null,
        /** @var FHIRPeriod|null period Time period plan covers */
        public ?FHIRPeriod $period = null,
        /** @var FHIRDateTime|null created Date record was first recorded */
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null custodian Who is the designated responsible party */
        public ?FHIRReference $custodian = null,
        /** @var array<FHIRReference> contributor Who provided the content of the care plan */
        public array $contributor = [],
        /** @var array<FHIRReference> careTeam Who's involved in plan? */
        public array $careTeam = [],
        /** @var array<FHIRCodeableReference> addresses Health issues this plan addresses */
        public array $addresses = [],
        /** @var array<FHIRReference> supportingInfo Information considered as part of plan */
        public array $supportingInfo = [],
        /** @var array<FHIRReference> goal Desired outcome of plan */
        public array $goal = [],
        /** @var array<FHIRCarePlanActivity> activity Action to occur or has occurred as part of plan */
        public array $activity = [],
        /** @var array<FHIRAnnotation> note Comments about the plan */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
