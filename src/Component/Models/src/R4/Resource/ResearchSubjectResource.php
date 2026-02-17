<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResearchSubjectStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ResearchSubject',
    fhirVersion: 'R4',
)]
class ResearchSubjectResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for research subject in a study */
        public array $identifier = [],
        /** @var ResearchSubjectStatusType|null status candidate | eligible | follow-up | ineligible | not-registered | off-study | on-study | on-study-intervention | on-study-observation | pending-on-study | potential-candidate | screening | withdrawn */
        #[NotBlank]
        public ?ResearchSubjectStatusType $status = null,
        /** @var Period|null period Start and end of participation */
        public ?Period $period = null,
        /** @var Reference|null study Study subject is part of */
        #[NotBlank]
        public ?Reference $study = null,
        /** @var Reference|null individual Who is part of study */
        #[NotBlank]
        public ?Reference $individual = null,
        /** @var StringPrimitive|string|null assignedArm What path should be followed */
        public StringPrimitive|string|null $assignedArm = null,
        /** @var StringPrimitive|string|null actualArm What path was followed */
        public StringPrimitive|string|null $actualArm = null,
        /** @var Reference|null consent Agreement to participate in study */
        public ?Reference $consent = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
