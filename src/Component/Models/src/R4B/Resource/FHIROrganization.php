<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Organization
 *
 * @description A formally or informally recognized grouping of people or organizations formed for the purpose of achieving some form of collective action.  Includes companies, institutions, corporations, departments, community groups, healthcare practice groups, payer/insurer, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Organization', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Organization', fhirVersion: 'R4B')]
class FHIROrganization extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Identifies this organization  across multiple systems */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether the organization's record is still in active use */
        public ?\FHIRBoolean $active = null,
        /** @var array<FHIRCodeableConcept> type Kind of organization */
        public array $type = [],
        /** @var FHIRString|string|null name Name used for the organization */
        public \FHIRString|string|null $name = null,
        /** @var array<FHIRString|string> alias A list of alternate names that the organization is known as, or was known as in the past */
        public array $alias = [],
        /** @var array<FHIRContactPoint> telecom A contact detail for the organization */
        public array $telecom = [],
        /** @var array<FHIRAddress> address An address for the organization */
        public array $address = [],
        /** @var FHIRReference|null partOf The organization of which this organization forms a part */
        public ?\FHIRReference $partOf = null,
        /** @var array<FHIROrganizationContact> contact Contact for the organization for a certain purpose */
        public array $contact = [],
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to services operated for the organization */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
