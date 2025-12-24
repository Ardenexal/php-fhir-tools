<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ResearchSubject
 *
 * @description A physical entity which is the primary unit of operational and/or administrative interest in a study.
 */
#[FhirResource(
    type: 'ResearchSubject',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ResearchSubject',
    fhirVersion: 'R4B',
)]
class FHIRResearchSubject extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for research subject in a study */
        public array $identifier = [],
        /** @var FHIRResearchSubjectStatusType|null status candidate | eligible | follow-up | ineligible | not-registered | off-study | on-study | on-study-intervention | on-study-observation | pending-on-study | potential-candidate | screening | withdrawn */
        #[NotBlank]
        public ?FHIRResearchSubjectStatusType $status = null,
        /** @var FHIRPeriod|null period Start and end of participation */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null study Study subject is part of */
        #[NotBlank]
        public ?FHIRReference $study = null,
        /** @var FHIRReference|null individual Who is part of study */
        #[NotBlank]
        public ?FHIRReference $individual = null,
        /** @var FHIRString|string|null assignedArm What path should be followed */
        public FHIRString|string|null $assignedArm = null,
        /** @var FHIRString|string|null actualArm What path was followed */
        public FHIRString|string|null $actualArm = null,
        /** @var FHIRReference|null consent Agreement to participate in study */
        public ?FHIRReference $consent = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
