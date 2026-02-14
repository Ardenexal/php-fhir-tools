<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
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
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole\PractitionerRoleAvailableTime;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole\PractitionerRoleNotAvailable;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PractitionerRole
 *
 * @description A specific set of Roles/Locations/specialties/services that a practitioner may perform at an organization for a period of time.
 */
#[FhirResource(
    type: 'PractitionerRole',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
    fhirVersion: 'R4',
)]
class PractitionerRoleResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifiers that are specific to a role/location */
        public array $identifier = [],
        /** @var bool|null active Whether this practitioner role record is in active use */
        public ?bool $active = null,
        /** @var Period|null period The period during which the practitioner is authorized to perform in these role(s) */
        public ?Period $period = null,
        /** @var Reference|null practitioner Practitioner that is able to provide the defined services for the organization */
        public ?Reference $practitioner = null,
        /** @var Reference|null organization Organization where the roles are available */
        public ?Reference $organization = null,
        /** @var array<CodeableConcept> code Roles which this practitioner may perform */
        public array $code = [],
        /** @var array<CodeableConcept> specialty Specific specialty of the practitioner */
        public array $specialty = [],
        /** @var array<Reference> location The location(s) at which this practitioner provides care */
        public array $location = [],
        /** @var array<Reference> healthcareService The list of healthcare services that this worker provides for this role's Organization/Location(s) */
        public array $healthcareService = [],
        /** @var array<ContactPoint> telecom Contact details that are specific to the role/location/service */
        public array $telecom = [],
        /** @var array<PractitionerRoleAvailableTime> availableTime Times the Service Site is available */
        public array $availableTime = [],
        /** @var array<PractitionerRoleNotAvailable> notAvailable Not available during this time due to provided reason */
        public array $notAvailable = [],
        /** @var StringPrimitive|string|null availabilityExceptions Description of availability exceptions */
        public StringPrimitive|string|null $availabilityExceptions = null,
        /** @var array<Reference> endpoint Technical endpoints providing access to services operated for the practitioner with this role */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
