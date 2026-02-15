<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ImmunizationEvaluationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ImmunizationEvaluation',
    fhirVersion: 'R4',
)]
class ImmunizationEvaluationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var ImmunizationEvaluationStatusCodesType|null status completed | entered-in-error */
        #[NotBlank]
        public ?ImmunizationEvaluationStatusCodesType $status = null,
        /** @var Reference|null patient Who this evaluation is for */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var DateTimePrimitive|null date Date evaluation was performed */
        public ?DateTimePrimitive $date = null,
        /** @var Reference|null authority Who is responsible for publishing the recommendations */
        public ?Reference $authority = null,
        /** @var CodeableConcept|null targetDisease Evaluation target disease */
        #[NotBlank]
        public ?CodeableConcept $targetDisease = null,
        /** @var Reference|null immunizationEvent Immunization being evaluated */
        #[NotBlank]
        public ?Reference $immunizationEvent = null,
        /** @var CodeableConcept|null doseStatus Status of the dose relative to published recommendations */
        #[NotBlank]
        public ?CodeableConcept $doseStatus = null,
        /** @var array<CodeableConcept> doseStatusReason Reason for the dose status */
        public array $doseStatusReason = [],
        /** @var StringPrimitive|string|null description Evaluation notes */
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null series Name of vaccine series */
        public StringPrimitive|string|null $series = null,
        /** @var PositiveIntPrimitive|StringPrimitive|string|null doseNumberX Dose number within series */
        public PositiveIntPrimitive|StringPrimitive|string|null $doseNumberX = null,
        /** @var PositiveIntPrimitive|StringPrimitive|string|null seriesDosesX Recommended number of doses for immunity */
        public PositiveIntPrimitive|StringPrimitive|string|null $seriesDosesX = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
