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
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation
 *
 * @description Defines an affiliation/assotiation/relationship between 2 distinct oganizations, that is not a part-of relationship/sub-division relationship.
 */
#[FhirResource(
    type: 'OrganizationAffiliation',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation',
    fhirVersion: 'R4',
)]
class OrganizationAffiliationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifiers that are specific to this role */
        public array $identifier = [],
        /** @var bool|null active Whether this organization affiliation record is in active use */
        public ?bool $active = null,
        /** @var Period|null period The period during which the participatingOrganization is affiliated with the primary organization */
        public ?Period $period = null,
        /** @var Reference|null organization Organization where the role is available */
        public ?Reference $organization = null,
        /** @var Reference|null participatingOrganization Organization that provides/performs the role (e.g. providing services or is a member of) */
        public ?Reference $participatingOrganization = null,
        /** @var array<Reference> network Health insurance provider network in which the participatingOrganization provides the role's services (if defined) at the indicated locations (if defined) */
        public array $network = [],
        /** @var array<CodeableConcept> code Definition of the role the participatingOrganization plays */
        public array $code = [],
        /** @var array<CodeableConcept> specialty Specific specialty of the participatingOrganization in the context of the role */
        public array $specialty = [],
        /** @var array<Reference> location The location(s) at which the role occurs */
        public array $location = [],
        /** @var array<Reference> healthcareService Healthcare services provided through the role */
        public array $healthcareService = [],
        /** @var array<ContactPoint> telecom Contact details at the participatingOrganization relevant to this Affiliation */
        public array $telecom = [],
        /** @var array<Reference> endpoint Technical endpoints providing access to services operated for this role */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
