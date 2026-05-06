<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Practitioner\PractitionerCommunication;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Practitioner\PractitionerQualification;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Practitioner
 *
 * @description A person who is directly or indirectly involved in the provisioning of healthcare or related services.
 */
#[FhirResource(type: 'Practitioner', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Practitioner', fhirVersion: 'R5')]
class PractitionerResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier An identifier for the person as this agent */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var bool|null active Whether this practitioner's record is in active use */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $active = null,
        /** @var array<HumanName> name The name(s) associated with the practitioner */
        #[FhirProperty(
            fhirType: 'HumanName',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName',
        )]
        public array $name = [],
        /** @var array<ContactPoint> telecom A contact detail for the practitioner (that apply to all roles) */
        #[FhirProperty(
            fhirType: 'ContactPoint',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
        )]
        public array $telecom = [],
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AdministrativeGenderType $gender = null,
        /** @var DatePrimitive|null birthDate The date  on which the practitioner was born */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $birthDate = null,
        /** @var bool|DateTimePrimitive|null deceased Indicates if the practitioner is deceased or not */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'deceasedBoolean'],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'deceasedDateTime',
                ],
            ],
        )]
        public bool|DateTimePrimitive|null $deceased = null,
        /** @var array<Address> address Address(es) of the practitioner that are not role specific (typically home address) */
        #[FhirProperty(
            fhirType: 'Address',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Address',
        )]
        public array $address = [],
        /** @var array<Attachment> photo Image of the person */
        #[FhirProperty(
            fhirType: 'Attachment',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
        )]
        public array $photo = [],
        /** @var array<PractitionerQualification> qualification Qualifications, certifications, accreditations, licenses, training, etc. pertaining to the provision of care */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Practitioner\PractitionerQualification',
        )]
        public array $qualification = [],
        /** @var array<PractitionerCommunication> communication A language which may be used to communicate with the practitioner */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Practitioner\PractitionerCommunication',
        )]
        public array $communication = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
