<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Categorized monetary totals for the adjudication.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.total', fhirVersion: 'R5')]
class FHIRExplanationOfBenefitTotal extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category Type of adjudication information */
        #[NotBlank]
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRMoney|null amount Financial total for the category */
        #[NotBlank]
        public ?FHIRMoney $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
