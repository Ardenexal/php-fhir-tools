<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConsentProvisionTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConsentStateType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentPolicyBasis;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentProvision;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentVerification;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Community Based Collaborative Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Consent
 *
 * @description A record of a healthcare consumer’s  choices  or choices made on their behalf by a third party, which permits or denies identified recipient(s) or recipient role(s) to perform one or more actions within a given policy context, for specific purposes and periods of time.
 */
#[FhirResource(type: 'Consent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Consent', fhirVersion: 'R5')]
class ConsentResource extends AbstractDomainResource
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
        /** @var array<Identifier> identifier Identifier for this record (external references) */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var ConsentStateType|null status draft | active | inactive | not-done | entered-in-error | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/consent-state-codes|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'This element is labelled as a modifier because it is a status element that contains status entered-in-error which means that the resource should not be treated as valid')]
        public ?ConsentStateType $status = null,
        /** @var array<CodeableConcept> category Classification of the consent statement - for indexing/retrieval */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var Reference|null subject Who the consent applies to */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/Group',
        ])]
        public ?Reference $subject = null,
        /** @var DatePrimitive|null date Fully executed date of the consent */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $date = null,
        /** @var Period|null period Effective period for this Consent */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var array<Reference> grantor Who is granting rights according to the policy and rules */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/CareTeam',
            'http://hl7.org/fhir/StructureDefinition/HealthcareService',
            'http://hl7.org/fhir/StructureDefinition/Organization',
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/RelatedPerson',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
        ])]
        public array $grantor = [],
        /** @var array<Reference> grantee Who is agreeing to the policy and rules */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/CareTeam',
            'http://hl7.org/fhir/StructureDefinition/HealthcareService',
            'http://hl7.org/fhir/StructureDefinition/Organization',
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/RelatedPerson',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
        ])]
        public array $grantee = [],
        /** @var array<Reference> manager Consent workflow management */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/HealthcareService',
            'http://hl7.org/fhir/StructureDefinition/Organization',
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
        ])]
        public array $manager = [],
        /** @var array<Reference> controller Consent Enforcer */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/HealthcareService',
            'http://hl7.org/fhir/StructureDefinition/Organization',
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
        ])]
        public array $controller = [],
        /** @var array<Attachment> sourceAttachment Source from which this consent is taken */
        #[FhirProperty(
            fhirType: 'Attachment',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
        )]
        public array $sourceAttachment = [],
        /** @var array<Reference> sourceReference Source from which this consent is taken */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Consent',
            'http://hl7.org/fhir/StructureDefinition/DocumentReference',
            'http://hl7.org/fhir/StructureDefinition/Contract',
            'http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse',
        ])]
        public array $sourceReference = [],
        /** @var array<CodeableConcept> regulatoryBasis Regulations establishing base Consent */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $regulatoryBasis = [],
        /** @var ConsentPolicyBasis|null policyBasis Computable version of the backing policy */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ConsentPolicyBasis $policyBasis = null,
        /** @var array<Reference> policyText Human Readable Policy */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/DocumentReference'])]
        public array $policyText = [],
        /** @var array<ConsentVerification> verification Consent Verified by patient or family */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentVerification',
        )]
        public array $verification = [],
        /** @var ConsentProvisionTypeType|null decision deny | permit */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/consent-provision-type|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'Sets context for the values in .provision')]
        public ?ConsentProvisionTypeType $decision = null,
        /** @var array<ConsentProvision> provision Constraints to the base Consent.policyRule/Consent.policy */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentProvision',
        )]
        public array $provision = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
