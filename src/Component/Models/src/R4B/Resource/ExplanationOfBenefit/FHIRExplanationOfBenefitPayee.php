<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The party to be reimbursed for cost of the products and services according to the terms of the policy.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.payee', fhirVersion: 'R4B')]
class FHIRExplanationOfBenefitPayee extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Category of recipient */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRReference|null party Recipient reference */
        public ?\FHIRReference $party = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
