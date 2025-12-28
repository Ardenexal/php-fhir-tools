<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CareTeam
 *
 * @description The Care Team includes all the people and organizations who plan to participate in the coordination and delivery of care.
 */
#[FhirResource(type: 'CareTeam', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/CareTeam', fhirVersion: 'R5')]
class FHIRCareTeam extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External Ids for this team */
        public array $identifier = [],
        /** @var FHIRCareTeamStatusType|null status proposed | active | suspended | inactive | entered-in-error */
        public ?FHIRCareTeamStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category Type of team */
        public array $category = [],
        /** @var FHIRString|string|null name Name of the team, such as crisis assessment team */
        public FHIRString|string|null $name = null,
        /** @var FHIRReference|null subject Who care team is for */
        public ?FHIRReference $subject = null,
        /** @var FHIRPeriod|null period Time period team covers */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRCareTeamParticipant> participant Members of the team */
        public array $participant = [],
        /** @var array<FHIRCodeableReference> reason Why the care team exists */
        public array $reason = [],
        /** @var array<FHIRReference> managingOrganization Organization responsible for the care team */
        public array $managingOrganization = [],
        /** @var array<FHIRContactPoint> telecom A contact detail for the care team (that applies to all members) */
        public array $telecom = [],
        /** @var array<FHIRAnnotation> note Comments made about the CareTeam */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
