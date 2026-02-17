<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Organization\OrganizationContact;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Organization
 *
 * @description A formally or informally recognized grouping of people or organizations formed for the purpose of achieving some form of collective action.  Includes companies, institutions, corporations, departments, community groups, healthcare practice groups, payer/insurer, etc.
 */
#[FhirResource(type: 'Organization', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Organization', fhirVersion: 'R4')]
class OrganizationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Identifies this organization  across multiple systems */
        public array $identifier = [],
        /** @var bool|null active Whether the organization's record is still in active use */
        public ?bool $active = null,
        /** @var array<CodeableConcept> type Kind of organization */
        public array $type = [],
        /** @var StringPrimitive|string|null name Name used for the organization */
        public StringPrimitive|string|null $name = null,
        /** @var array<StringPrimitive|string> alias A list of alternate names that the organization is known as, or was known as in the past */
        public array $alias = [],
        /** @var array<ContactPoint> telecom A contact detail for the organization */
        public array $telecom = [],
        /** @var array<Address> address An address for the organization */
        public array $address = [],
        /** @var Reference|null partOf The organization of which this organization forms a part */
        public ?Reference $partOf = null,
        /** @var array<OrganizationContact> contact Contact for the organization for a certain purpose */
        public array $contact = [],
        /** @var array<Reference> endpoint Technical endpoints providing access to services operated for the organization */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
