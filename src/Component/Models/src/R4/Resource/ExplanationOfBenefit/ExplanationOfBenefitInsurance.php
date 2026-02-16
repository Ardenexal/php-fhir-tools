<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Financial instruments for reimbursement for the health care products and services specified on the claim.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.insurance', fhirVersion: 'R4')]
class ExplanationOfBenefitInsurance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null focal Coverage to be used for adjudication */
        #[NotBlank]
        public ?bool $focal = null,
        /** @var Reference|null coverage Insurance information */
        #[NotBlank]
        public ?Reference $coverage = null,
        /** @var array<StringPrimitive|string> preAuthRef Prior authorization reference number */
        public array $preAuthRef = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
