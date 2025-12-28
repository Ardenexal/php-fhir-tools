<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Public Health and Emergency Response)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImmunizationEvaluation
 *
 * @description Describes a comparison of an immunization event against published recommendations to determine if the administration is "valid" in relation to those  recommendations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'ImmunizationEvaluation',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ImmunizationEvaluation',
    fhirVersion: 'R4B',
)]
class FHIRImmunizationEvaluation extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FHIRImmunizationEvaluationStatusCodesType|null status completed | entered-in-error */
        #[NotBlank]
        public ?\FHIRImmunizationEvaluationStatusCodesType $status = null,
        /** @var FHIRReference|null patient Who this evaluation is for */
        #[NotBlank]
        public ?\FHIRReference $patient = null,
        /** @var FHIRDateTime|null date Date evaluation was performed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRReference|null authority Who is responsible for publishing the recommendations */
        public ?\FHIRReference $authority = null,
        /** @var FHIRCodeableConcept|null targetDisease Evaluation target disease */
        #[NotBlank]
        public ?\FHIRCodeableConcept $targetDisease = null,
        /** @var FHIRReference|null immunizationEvent Immunization being evaluated */
        #[NotBlank]
        public ?\FHIRReference $immunizationEvent = null,
        /** @var FHIRCodeableConcept|null doseStatus Status of the dose relative to published recommendations */
        #[NotBlank]
        public ?\FHIRCodeableConcept $doseStatus = null,
        /** @var array<FHIRCodeableConcept> doseStatusReason Reason for the dose status */
        public array $doseStatusReason = [],
        /** @var FHIRString|string|null description Evaluation notes */
        public \FHIRString|string|null $description = null,
        /** @var FHIRString|string|null series Name of vaccine series */
        public \FHIRString|string|null $series = null,
        /** @var FHIRPositiveInt|FHIRString|string|null doseNumberX Dose number within series */
        public \FHIRPositiveInt|\FHIRString|string|null $doseNumberX = null,
        /** @var FHIRPositiveInt|FHIRString|string|null seriesDosesX Recommended number of doses for immunity */
        public \FHIRPositiveInt|\FHIRString|string|null $seriesDosesX = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
