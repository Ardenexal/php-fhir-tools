<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\VerificationResultStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\VerificationResult\VerificationResultAttestation;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\VerificationResult\VerificationResultPrimarySource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\VerificationResult\VerificationResultValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/VerificationResult
 *
 * @description Describes validation requirements, source(s), status and dates for one or more elements.
 */
#[FhirResource(
    type: 'VerificationResult',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/VerificationResult',
    fhirVersion: 'R5',
)]
class VerificationResultResource extends DomainResourceResource
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
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var array<Reference> target A resource that was validated */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Resource'])]
        public array $target = [],
        /** @var array<StringPrimitive|string> targetLocation The fhirpath location(s) within the resource that was validated */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $targetLocation = [],
        /** @var CodeableConcept|null need none | initial | periodic */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/verificationresult-need', strength: 'preferred')]
        public ?CodeableConcept $need = null,
        /** @var VerificationResultStatusType|null status attested | validated | in-process | req-revalid | val-fail | reval-fail | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/verificationresult-status|5.0.0', strength: 'required')]
        public ?VerificationResultStatusType $status = null,
        /** @var DateTimePrimitive|null statusDate When the validation status was updated */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $statusDate = null,
        /** @var CodeableConcept|null validationType nothing | primary | multiple */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/verificationresult-validation-type', strength: 'preferred')]
        public ?CodeableConcept $validationType = null,
        /** @var array<CodeableConcept> validationProcess The primary process by which the target is validated (edit check; value set; primary source; multiple sources; standalone; in context) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $validationProcess = [],
        /** @var Timing|null frequency Frequency of revalidation */
        #[FhirProperty(fhirType: 'Timing', propertyKind: 'complex')]
        public ?Timing $frequency = null,
        /** @var DateTimePrimitive|null lastPerformed The date/time validation was last completed (including failed validations) */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $lastPerformed = null,
        /** @var DatePrimitive|null nextScheduled The date when target is next validated, if appropriate */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $nextScheduled = null,
        /** @var CodeableConcept|null failureAction fatal | warn | rec-only | none */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/verificationresult-failure-action', strength: 'preferred')]
        public ?CodeableConcept $failureAction = null,
        /** @var array<VerificationResultPrimarySource> primarySource Information about the primary source(s) involved in validation */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\VerificationResult\VerificationResultPrimarySource',
        )]
        public array $primarySource = [],
        /** @var VerificationResultAttestation|null attestation Information about the entity attesting to information */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?VerificationResultAttestation $attestation = null,
        /** @var array<VerificationResultValidator> validator Information about the entity validating information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\VerificationResult\VerificationResultValidator',
        )]
        public array $validator = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
