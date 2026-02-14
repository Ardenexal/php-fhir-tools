<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult\VerificationResultAttestation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult\VerificationResultPrimarySource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult\VerificationResultValidator;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/VerificationResult',
    fhirVersion: 'R4',
)]
class VerificationResultResource extends DomainResourceResource
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
        /** @var array<Reference> target A resource that was validated */
        public array $target = [],
        /** @var array<StringPrimitive|string> targetLocation The fhirpath location(s) within the resource that was validated */
        public array $targetLocation = [],
        /** @var CodeableConcept|null need none | initial | periodic */
        public ?CodeableConcept $need = null,
        /** @var StatusType|null status attested | validated | in-process | req-revalid | val-fail | reval-fail */
        #[NotBlank]
        public ?StatusType $status = null,
        /** @var DateTimePrimitive|null statusDate When the validation status was updated */
        public ?DateTimePrimitive $statusDate = null,
        /** @var CodeableConcept|null validationType nothing | primary | multiple */
        public ?CodeableConcept $validationType = null,
        /** @var array<CodeableConcept> validationProcess The primary process by which the target is validated (edit check; value set; primary source; multiple sources; standalone; in context) */
        public array $validationProcess = [],
        /** @var Timing|null frequency Frequency of revalidation */
        public ?Timing $frequency = null,
        /** @var DateTimePrimitive|null lastPerformed The date/time validation was last completed (including failed validations) */
        public ?DateTimePrimitive $lastPerformed = null,
        /** @var DatePrimitive|null nextScheduled The date when target is next validated, if appropriate */
        public ?DatePrimitive $nextScheduled = null,
        /** @var CodeableConcept|null failureAction fatal | warn | rec-only | none */
        public ?CodeableConcept $failureAction = null,
        /** @var array<VerificationResultPrimarySource> primarySource Information about the primary source(s) involved in validation */
        public array $primarySource = [],
        /** @var VerificationResultAttestation|null attestation Information about the entity attesting to information */
        public ?VerificationResultAttestation $attestation = null,
        /** @var array<VerificationResultValidator> validator Information about the entity validating information */
        public array $validator = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
