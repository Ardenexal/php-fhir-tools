<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRImmunizationEvaluationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Public Health and Emergency Response)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImmunizationEvaluation
 *
 * @description Describes a comparison of an immunization event against published recommendations to determine if the administration is "valid" in relation to those  recommendations.
 */
#[FhirResource(
    type: 'ImmunizationEvaluation',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ImmunizationEvaluation',
    fhirVersion: 'R5',
)]
class FHIRImmunizationEvaluation extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FHIRImmunizationEvaluationStatusCodesType|null status completed | entered-in-error */
        #[NotBlank]
        public ?FHIRImmunizationEvaluationStatusCodesType $status = null,
        /** @var FHIRReference|null patient Who this evaluation is for */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRDateTime|null date Date evaluation was performed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRReference|null authority Who is responsible for publishing the recommendations */
        public ?FHIRReference $authority = null,
        /** @var FHIRCodeableConcept|null targetDisease The vaccine preventable disease schedule being evaluated */
        #[NotBlank]
        public ?FHIRCodeableConcept $targetDisease = null,
        /** @var FHIRReference|null immunizationEvent Immunization being evaluated */
        #[NotBlank]
        public ?FHIRReference $immunizationEvent = null,
        /** @var FHIRCodeableConcept|null doseStatus Status of the dose relative to published recommendations */
        #[NotBlank]
        public ?FHIRCodeableConcept $doseStatus = null,
        /** @var array<FHIRCodeableConcept> doseStatusReason Reason why the doese is considered valid, invalid or some other status */
        public array $doseStatusReason = [],
        /** @var FHIRMarkdown|null description Evaluation notes */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRString|string|null series Name of vaccine series */
        public FHIRString|string|null $series = null,
        /** @var FHIRString|string|null doseNumber Dose number within series */
        public FHIRString|string|null $doseNumber = null,
        /** @var FHIRString|string|null seriesDoses Recommended number of doses for immunity */
        public FHIRString|string|null $seriesDoses = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
