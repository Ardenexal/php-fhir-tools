<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Benefits used to date.
 */
#[FHIRBackboneElement(
    parentResource: 'CoverageEligibilityResponse',
    elementPath: 'CoverageEligibilityResponse.insurance.item.benefit',
    fhirVersion: 'R4',
)]
class CoverageEligibilityResponseInsuranceItemBenefit extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Benefit classification */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var UnsignedIntPrimitive|StringPrimitive|string|Money|null allowedX Benefits allowed */
        public UnsignedIntPrimitive|StringPrimitive|string|Money|null $allowedX = null,
        /** @var UnsignedIntPrimitive|StringPrimitive|string|Money|null usedX Benefits used */
        public UnsignedIntPrimitive|StringPrimitive|string|Money|null $usedX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
