<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional information codes regarding exceptions, special considerations, the condition, situation, prior or concurrent issues.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.supportingInfo', fhirVersion: 'R4')]
class FHIRExplanationOfBenefitSupportingInfo extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Information instance identifier */
        #[NotBlank]
        public ?FHIRPositiveInt $sequence = null,
        /** @var FHIRCodeableConcept|null category Classification of the supplied information */
        #[NotBlank]
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null code Type of information */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRDate|FHIRPeriod|null timingX When it occurred */
        public FHIRDate|FHIRPeriod|null $timingX = null,
        /** @var FHIRBoolean|FHIRString|string|FHIRQuantity|FHIRAttachment|FHIRReference|null valueX Data to be provided */
        public FHIRBoolean|FHIRString|string|FHIRQuantity|FHIRAttachment|FHIRReference|null $valueX = null,
        /** @var FHIRCoding|null reason Explanation for the information */
        public ?FHIRCoding $reason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
