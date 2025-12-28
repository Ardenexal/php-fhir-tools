<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Details of a accident which resulted in injuries which required the products and services listed in the claim.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.accident', fhirVersion: 'R4')]
class FHIRExplanationOfBenefitAccident extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDate|null date When the incident occurred */
        public ?\FHIRDate $date = null,
        /** @var FHIRCodeableConcept|null type The nature of the accident */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRAddress|FHIRReference|null locationX Where the event occurred */
        public \FHIRAddress|\FHIRReference|null $locationX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
