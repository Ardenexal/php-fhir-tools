<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional information codes regarding exceptions, special considerations, the condition, situation, prior or concurrent issues.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.supportingInfo', fhirVersion: 'R4')]
class ExplanationOfBenefitSupportingInfo extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null sequence Information instance identifier */
        #[NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
        /** @var CodeableConcept|null category Classification of the supplied information */
        #[NotBlank]
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null code Type of information */
        public ?CodeableConcept $code = null,
        /** @var DatePrimitive|Period|null timingX When it occurred */
        public DatePrimitive|Period|null $timingX = null,
        /** @var bool|StringPrimitive|string|Quantity|Attachment|Reference|null valueX Data to be provided */
        public bool|StringPrimitive|string|Quantity|Attachment|Reference|null $valueX = null,
        /** @var Coding|null reason Explanation for the information */
        public ?Coding $reason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
