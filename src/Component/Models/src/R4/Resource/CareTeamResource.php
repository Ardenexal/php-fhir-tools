<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CareTeamStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CareTeam\CareTeamParticipant;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CareTeam
 *
 * @description The Care Team includes all the people and organizations who plan to participate in the coordination and delivery of care for a patient.
 */
#[FhirResource(type: 'CareTeam', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/CareTeam', fhirVersion: 'R4')]
class CareTeamResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External Ids for this team */
        public array $identifier = [],
        /** @var CareTeamStatusType|null status proposed | active | suspended | inactive | entered-in-error */
        public ?CareTeamStatusType $status = null,
        /** @var array<CodeableConcept> category Type of team */
        public array $category = [],
        /** @var StringPrimitive|string|null name Name of the team, such as crisis assessment team */
        public StringPrimitive|string|null $name = null,
        /** @var Reference|null subject Who care team is for */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var Period|null period Time period team covers */
        public ?Period $period = null,
        /** @var array<CareTeamParticipant> participant Members of the team */
        public array $participant = [],
        /** @var array<CodeableConcept> reasonCode Why the care team exists */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why the care team exists */
        public array $reasonReference = [],
        /** @var array<Reference> managingOrganization Organization responsible for the care team */
        public array $managingOrganization = [],
        /** @var array<ContactPoint> telecom A contact detail for the care team (that applies to all members) */
        public array $telecom = [],
        /** @var array<Annotation> note Comments made about the CareTeam */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
