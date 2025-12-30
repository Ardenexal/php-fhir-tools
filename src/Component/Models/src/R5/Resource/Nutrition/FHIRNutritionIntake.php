<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIREventStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NutritionIntake
 *
 * @description A record of food or fluid that is being consumed by a patient.   A NutritionIntake may indicate that the patient may be consuming the food or fluid now or has consumed the food or fluid in the past.  The source of this information can be the patient, significant other (such as a family member or spouse), or a clinician.  A common scenario where this information is captured is during the history taking process during a patient visit or stay or through an app that tracks food or fluids consumed.   The consumption information may come from sources such as the patient's memory, from a nutrition label,  or from a clinician documenting observed intake.
 */
#[FhirResource(
    type: 'NutritionIntake',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/NutritionIntake',
    fhirVersion: 'R5',
)]
class FHIRNutritionIntake extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn Fulfils plan, proposal or order */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIREventStatusType|null status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIREventStatusType $status = null,
        /** @var array<FHIRCodeableConcept> statusReason Reason for current status */
        public array $statusReason = [],
        /** @var FHIRCodeableConcept|null code Code representing an overall type of nutrition intake */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Who is/was consuming the food or fluid */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter associated with NutritionIntake */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|null occurrenceX The date/time or interval when the food or fluid is/was consumed */
        public FHIRDateTime|FHIRPeriod|null $occurrenceX = null,
        /** @var FHIRDateTime|null recorded When the intake was recorded */
        public ?FHIRDateTime $recorded = null,
        /** @var FHIRBoolean|FHIRReference|null reportedX Person or organization that provided the information about the consumption of this food or fluid */
        public FHIRBoolean|FHIRReference|null $reportedX = null,
        /** @var array<FHIRNutritionIntakeConsumedItem> consumedItem What food or fluid product or item was consumed */
        public array $consumedItem = [],
        /** @var array<FHIRNutritionIntakeIngredientLabel> ingredientLabel Total nutrient for the whole meal, product, serving */
        public array $ingredientLabel = [],
        /** @var array<FHIRNutritionIntakePerformer> performer Who was performed in the intake */
        public array $performer = [],
        /** @var FHIRReference|null location Where the intake occurred */
        public ?FHIRReference $location = null,
        /** @var array<FHIRReference> derivedFrom Additional supporting information */
        public array $derivedFrom = [],
        /** @var array<FHIRCodeableReference> reason Reason for why the food or fluid is /was consumed */
        public array $reason = [],
        /** @var array<FHIRAnnotation> note Further information about the consumption */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
