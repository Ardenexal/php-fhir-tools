<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Citation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @description An individual entity named in the author list or contributor list.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship.entry', fhirVersion: 'R4B')]
class CitationCitedArtifactContributorshipEntry extends BackboneElement
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'name' => [
            'fhirType'     => 'HumanName',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'initials' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'collectiveName' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'identifier' => [
            'fhirType'     => 'Identifier',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'affiliationInfo' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'address' => [
            'fhirType'     => 'Address',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'telecom' => [
            'fhirType'     => 'ContactPoint',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'contributionType' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'role' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'contributionInstance' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'correspondingContact' => [
            'fhirType'     => 'boolean',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'listOrder' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var HumanName|null name A name associated with the person */
        #[FhirProperty(fhirType: 'HumanName', propertyKind: 'complex')]
        public ?HumanName $name = null,
        /** @var StringPrimitive|string|null initials Initials for forename */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $initials = null,
        /** @var StringPrimitive|string|null collectiveName Used for collective or corporate name as an author */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $collectiveName = null,
        /** @var array<Identifier> identifier Author identifier, eg ORCID */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex', isArray: true)]
        public array $identifier = [],
        /** @var array<CitationCitedArtifactContributorshipEntryAffiliationInfo> affiliationInfo Organizational affiliation */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $affiliationInfo = [],
        /** @var array<Address> address Physical mailing address */
        #[FhirProperty(fhirType: 'Address', propertyKind: 'complex', isArray: true)]
        public array $address = [],
        /** @var array<ContactPoint> telecom Email or telephone contact methods for the author or contributor */
        #[FhirProperty(fhirType: 'ContactPoint', propertyKind: 'complex', isArray: true)]
        public array $telecom = [],
        /** @var array<CodeableConcept> contributionType The specific contribution */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $contributionType = [],
        /** @var CodeableConcept|null role The role of the contributor (e.g. author, editor, reviewer) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $role = null,
        /** @var array<CitationCitedArtifactContributorshipEntryContributionInstance> contributionInstance Contributions with accounting for time or number */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $contributionInstance = [],
        /** @var bool|null correspondingContact Indication of which contributor is the corresponding contributor for the role */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $correspondingContact = null,
        /** @var PositiveIntPrimitive|null listOrder Used to code order of authors */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $listOrder = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
