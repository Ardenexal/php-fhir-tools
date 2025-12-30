<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation
 *
 * @description Defines an affiliation/assotiation/relationship between 2 distinct oganizations, that is not a part-of relationship/sub-division relationship.
 */
#[FhirResource(
    type: 'OrganizationAffiliation',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation',
    fhirVersion: 'R4B',
)]
class FHIROrganizationAffiliation extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifiers that are specific to this role */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this organization affiliation record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var FHIRPeriod|null period The period during which the participatingOrganization is affiliated with the primary organization */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null organization Organization where the role is available */
        public ?FHIRReference $organization = null,
        /** @var FHIRReference|null participatingOrganization Organization that provides/performs the role (e.g. providing services or is a member of) */
        public ?FHIRReference $participatingOrganization = null,
        /** @var array<FHIRReference> network Health insurance provider network in which the participatingOrganization provides the role's services (if defined) at the indicated locations (if defined) */
        public array $network = [],
        /** @var array<FHIRCodeableConcept> code Definition of the role the participatingOrganization plays */
        public array $code = [],
        /** @var array<FHIRCodeableConcept> specialty Specific specialty of the participatingOrganization in the context of the role */
        public array $specialty = [],
        /** @var array<FHIRReference> location The location(s) at which the role occurs */
        public array $location = [],
        /** @var array<FHIRReference> healthcareService Healthcare services provided through the role */
        public array $healthcareService = [],
        /** @var array<FHIRContactPoint> telecom Contact details at the participatingOrganization relevant to this Affiliation */
        public array $telecom = [],
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to services operated for this role */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
