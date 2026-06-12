<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Availability;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\LocationModeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\LocationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\VirtualServiceDetail;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Location\LocationPosition;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Location
 *
 * @description Details and position information for a place where services are provided and resources and participants may be stored, found, contained, or accommodated.
 */
#[FhirResource(type: 'Location', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Location', fhirVersion: 'R5')]
class LocationResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies its meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages|5.0.0', strength: 'required')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Unique code or number identifying the location to its users */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var LocationStatusType|null status active | suspended | inactive */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/location-status|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the status contains codes that mark the resource as not currently valid')]
        public ?LocationStatusType $status = null,
        /** @var Coding|null operationalStatus The operational status of the location (typically only for a bed/room) */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://terminology.hl7.org/ValueSet/v2-0116', strength: 'preferred')]
        public ?Coding $operationalStatus = null,
        /** @var StringPrimitive|string|null name Name of the location as used by humans */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var array<StringPrimitive|string> alias A list of alternate names that the location is known as, or was known as, in the past */
        #[FhirProperty(
            fhirType: 'string',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
        )]
        public array $alias = [],
        /** @var MarkdownPrimitive|null description Additional details about the location that could be displayed as further information to identify the location beyond its name */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var LocationModeType|null mode instance | kind */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/location-mode|5.0.0', strength: 'required')]
        public ?LocationModeType $mode = null,
        /** @var array<CodeableConcept> type Type of function performed */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://terminology.hl7.org/ValueSet/v3-ServiceDeliveryLocationRoleType', strength: 'extensible')]
        public array $type = [],
        /** @var array<ExtendedContactDetail> contact Official contact details for the location */
        #[FhirProperty(
            fhirType: 'ExtendedContactDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
        )]
        public array $contact = [],
        /** @var Address|null address Physical location */
        #[FhirProperty(fhirType: 'Address', propertyKind: 'complex')]
        public ?Address $address = null,
        /** @var CodeableConcept|null form Physical form of the location */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $form = null,
        /** @var LocationPosition|null position The absolute geographic location */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?LocationPosition $position = null,
        /** @var Reference|null managingOrganization Organization responsible for provisioning and upkeep */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Organization'])]
        public ?Reference $managingOrganization = null,
        /** @var Reference|null partOf Another Location this one is physically a part of */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Location'])]
        public ?Reference $partOf = null,
        /** @var array<CodeableConcept> characteristic Collection of characteristics (attributes) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $characteristic = [],
        /** @var array<Availability> hoursOfOperation What days/times during a week is this location usually open (including exceptions) */
        #[FhirProperty(
            fhirType: 'Availability',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Availability',
        )]
        public array $hoursOfOperation = [],
        /** @var array<VirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
        #[FhirProperty(
            fhirType: 'VirtualServiceDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\VirtualServiceDetail',
        )]
        public array $virtualService = [],
        /** @var array<Reference> endpoint Technical endpoints providing access to services operated for the location */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Endpoint'])]
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
