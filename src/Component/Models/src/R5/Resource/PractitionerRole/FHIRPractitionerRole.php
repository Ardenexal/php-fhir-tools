<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAvailability;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtendedContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PractitionerRole
 *
 * @description A specific set of Roles/Locations/specialties/services that a practitioner may perform at an organization for a period of time.
 */
#[FhirResource(
    type: 'PractitionerRole',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
    fhirVersion: 'R5',
)]
class FHIRPractitionerRole extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Identifiers for a role/location */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this practitioner role record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var FHIRPeriod|null period The period during which the practitioner is authorized to perform in these role(s) */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null practitioner Practitioner that provides services for the organization */
        public ?FHIRReference $practitioner = null,
        /** @var FHIRReference|null organization Organization where the roles are available */
        public ?FHIRReference $organization = null,
        /** @var array<FHIRCodeableConcept> code Roles which this practitioner may perform */
        public array $code = [],
        /** @var array<FHIRCodeableConcept> specialty Specific specialty of the practitioner */
        public array $specialty = [],
        /** @var array<FHIRReference> location Location(s) where the practitioner provides care */
        public array $location = [],
        /** @var array<FHIRReference> healthcareService Healthcare services provided for this role's Organization/Location(s) */
        public array $healthcareService = [],
        /** @var array<FHIRExtendedContactDetail> contact Official contact details relating to this PractitionerRole */
        public array $contact = [],
        /** @var array<FHIRCodeableConcept> characteristic Collection of characteristics (attributes) */
        public array $characteristic = [],
        /** @var array<FHIRCodeableConcept> communication A language the practitioner (in this role) can use in patient communication */
        public array $communication = [],
        /** @var array<FHIRAvailability> availability Times the Practitioner is available at this location and/or healthcare service (including exceptions) */
        public array $availability = [],
        /** @var array<FHIRReference> endpoint Endpoints for interacting with the practitioner in this role */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
