<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ConsentStateType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentPolicy;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentProvision;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentVerification;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Community Based Collaborative Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Consent
 *
 * @description A record of a healthcare consumer’s  choices, which permits or denies identified recipient(s) or recipient role(s) to perform one or more actions within a given policy context, for specific purposes and periods of time.
 */
#[FhirResource(type: 'Consent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Consent', fhirVersion: 'R4')]
class ConsentResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Identifier for this record (external references) */
        public array $identifier = [],
        /** @var ConsentStateType|null status draft | proposed | active | rejected | inactive | entered-in-error */
        #[NotBlank]
        public ?ConsentStateType $status = null,
        /** @var CodeableConcept|null scope Which of the four areas this resource covers (extensible) */
        #[NotBlank]
        public ?CodeableConcept $scope = null,
        /** @var array<CodeableConcept> category Classification of the consent statement - for indexing/retrieval */
        public array $category = [],
        /** @var Reference|null patient Who the consent applies to */
        public ?Reference $patient = null,
        /** @var DateTimePrimitive|null dateTime When this Consent was created or indexed */
        public ?DateTimePrimitive $dateTime = null,
        /** @var array<Reference> performer Who is agreeing to the policy and rules */
        public array $performer = [],
        /** @var array<Reference> organization Custodian of the consent */
        public array $organization = [],
        /** @var Attachment|Reference|null sourceX Source from which this consent is taken */
        public Attachment|Reference|null $sourceX = null,
        /** @var array<ConsentPolicy> policy Policies covered by this consent */
        public array $policy = [],
        /** @var CodeableConcept|null policyRule Regulation that this consents to */
        public ?CodeableConcept $policyRule = null,
        /** @var array<ConsentVerification> verification Consent Verified by patient or family */
        public array $verification = [],
        /** @var ConsentProvision|null provision Constraints to the base Consent.policyRule */
        public ?ConsentProvision $provision = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
