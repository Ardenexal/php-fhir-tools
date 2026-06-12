<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
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
class PractitionerRoleResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies it\'s meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/languages',
            strength: 'preferred',
            maxValueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages',
        )]
        public ?string $language = null,
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
        /** @var array<Identifier> identifier Business Identifiers that are specific to a role/location */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var bool|null active Whether this practitioner role record is in active use */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $active = null,
        /** @var Period|null period The period during which the practitioner is authorized to perform in these role(s) */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var Reference|null practitioner Practitioner that is able to provide the defined services for the organization */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Practitioner'])]
        public ?Reference $practitioner = null,
        /** @var Reference|null organization Organization where the roles are available */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Organization'])]
        public ?Reference $organization = null,
        /** @var array<CodeableConcept> code Roles which this practitioner may perform */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $code = [],
        /** @var array<CodeableConcept> specialty Specific specialty of the practitioner */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/c80-practice-codes', strength: 'preferred')]
        public array $specialty = [],
        /** @var array<Reference> location The location(s) at which this practitioner provides care */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Location'])]
        public array $location = [],
        /** @var array<Reference> healthcareService The list of healthcare services that this worker provides for this role's Organization/Location(s) */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/HealthcareService'])]
        public array $healthcareService = [],
        /** @var array<ContactPoint> telecom Contact details that are specific to the role/location/service */
        #[FhirProperty(
            fhirType: 'ContactPoint',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint',
        )]
        public array $telecom = [],
        /** @var array<PractitionerRoleAvailableTime> availableTime Times the Service Site is available */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole\PractitionerRoleAvailableTime',
        )]
        public array $availableTime = [],
        /** @var array<PractitionerRoleNotAvailable> notAvailable Not available during this time due to provided reason */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole\PractitionerRoleNotAvailable',
        )]
        public array $notAvailable = [],
        /** @var StringPrimitive|string|null availabilityExceptions Description of availability exceptions */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $availabilityExceptions = null,
        /** @var array<Reference> endpoint Technical endpoints providing access to services operated for the practitioner with this role */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Endpoint'])]
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
