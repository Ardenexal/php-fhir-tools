<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description If this item is a group then the values here are a summary of the adjudication of the detail items. If this item is a simple product or service then this is the result of the adjudication of this item.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.adjudication', fhirVersion: 'R4')]
class ClaimResponseItemAdjudication extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null category Type of adjudication information */
        #[NotBlank]
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null reason Explanation of adjudication outcome */
        public ?CodeableConcept $reason = null,
        /** @var Money|null amount Monetary amount */
        public ?Money $amount = null,
        /** @var float|null value Non-monetary value */
        public ?float $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
