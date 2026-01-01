<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVerificationResultStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
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
class FHIRVerificationResult extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> target A resource that was validated */
        public array $target = [],
        /** @var array<FHIRString|string> targetLocation The fhirpath location(s) within the resource that was validated */
        public array $targetLocation = [],
        /** @var FHIRCodeableConcept|null need none | initial | periodic */
        public ?FHIRCodeableConcept $need = null,
        /** @var FHIRVerificationResultStatusType|null status attested | validated | in-process | req-revalid | val-fail | reval-fail | entered-in-error */
        #[NotBlank]
        public ?FHIRVerificationResultStatusType $status = null,
        /** @var FHIRDateTime|null statusDate When the validation status was updated */
        public ?FHIRDateTime $statusDate = null,
        /** @var FHIRCodeableConcept|null validationType nothing | primary | multiple */
        public ?FHIRCodeableConcept $validationType = null,
        /** @var array<FHIRCodeableConcept> validationProcess The primary process by which the target is validated (edit check; value set; primary source; multiple sources; standalone; in context) */
        public array $validationProcess = [],
        /** @var FHIRTiming|null frequency Frequency of revalidation */
        public ?FHIRTiming $frequency = null,
        /** @var FHIRDateTime|null lastPerformed The date/time validation was last completed (including failed validations) */
        public ?FHIRDateTime $lastPerformed = null,
        /** @var FHIRDate|null nextScheduled The date when target is next validated, if appropriate */
        public ?FHIRDate $nextScheduled = null,
        /** @var FHIRCodeableConcept|null failureAction fatal | warn | rec-only | none */
        public ?FHIRCodeableConcept $failureAction = null,
        /** @var array<FHIRVerificationResultPrimarySource> primarySource Information about the primary source(s) involved in validation */
        public array $primarySource = [],
        /** @var FHIRVerificationResultAttestation|null attestation Information about the entity attesting to information */
        public ?FHIRVerificationResultAttestation $attestation = null,
        /** @var array<FHIRVerificationResultValidator> validator Information about the entity validating information */
        public array $validator = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
