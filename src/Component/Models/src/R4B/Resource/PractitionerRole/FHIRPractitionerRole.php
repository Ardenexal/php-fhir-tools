<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PractitionerRole
 *
 * @description A specific set of Roles/Locations/specialties/services that a practitioner may perform at an organization for a period of time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'PractitionerRole',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
    fhirVersion: 'R4B',
)]
class FHIRPractitionerRole extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifiers that are specific to a role/location */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this practitioner role record is in active use */
        public ?\FHIRBoolean $active = null,
        /** @var FHIRPeriod|null period The period during which the practitioner is authorized to perform in these role(s) */
        public ?\FHIRPeriod $period = null,
        /** @var FHIRReference|null practitioner Practitioner that is able to provide the defined services for the organization */
        public ?\FHIRReference $practitioner = null,
        /** @var FHIRReference|null organization Organization where the roles are available */
        public ?\FHIRReference $organization = null,
        /** @var array<FHIRCodeableConcept> code Roles which this practitioner may perform */
        public array $code = [],
        /** @var array<FHIRCodeableConcept> specialty Specific specialty of the practitioner */
        public array $specialty = [],
        /** @var array<FHIRReference> location The location(s) at which this practitioner provides care */
        public array $location = [],
        /** @var array<FHIRReference> healthcareService The list of healthcare services that this worker provides for this role's Organization/Location(s) */
        public array $healthcareService = [],
        /** @var array<FHIRContactPoint> telecom Contact details that are specific to the role/location/service */
        public array $telecom = [],
        /** @var array<FHIRPractitionerRoleAvailableTime> availableTime Times the Service Site is available */
        public array $availableTime = [],
        /** @var array<FHIRPractitionerRoleNotAvailable> notAvailable Not available during this time due to provided reason */
        public array $notAvailable = [],
        /** @var FHIRString|string|null availabilityExceptions Description of availability exceptions */
        public \FHIRString|string|null $availabilityExceptions = null,
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to services operated for the practitioner with this role */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
