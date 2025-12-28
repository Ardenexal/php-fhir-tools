<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation
 *
 * @description Defines an affiliation/association/relationship between 2 distinct organizations, that is not a part-of relationship/sub-division relationship.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'OrganizationAffiliation',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation',
    fhirVersion: 'R5',
)]
class FHIROrganizationAffiliation extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifiers that are specific to this role */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this organization affiliation record is in active use */
        public ?\FHIRBoolean $active = null,
        /** @var FHIRPeriod|null period The period during which the participatingOrganization is affiliated with the primary organization */
        public ?\FHIRPeriod $period = null,
        /** @var FHIRReference|null organization Organization where the role is available */
        public ?\FHIRReference $organization = null,
        /** @var FHIRReference|null participatingOrganization Organization that provides/performs the role (e.g. providing services or is a member of) */
        public ?\FHIRReference $participatingOrganization = null,
        /** @var array<FHIRReference> network The network in which the participatingOrganization provides the role's services (if defined) at the indicated locations (if defined) */
        public array $network = [],
        /** @var array<FHIRCodeableConcept> code Definition of the role the participatingOrganization plays */
        public array $code = [],
        /** @var array<FHIRCodeableConcept> specialty Specific specialty of the participatingOrganization in the context of the role */
        public array $specialty = [],
        /** @var array<FHIRReference> location The location(s) at which the role occurs */
        public array $location = [],
        /** @var array<FHIRReference> healthcareService Healthcare services provided through the role */
        public array $healthcareService = [],
        /** @var array<FHIRExtendedContactDetail> contact Official contact details at the participatingOrganization relevant to this Affiliation */
        public array $contact = [],
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to services operated for this role */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
