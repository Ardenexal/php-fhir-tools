<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Details of an accident which resulted in injuries which required the products and services listed in the claim.
 */
#[FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.accident', fhirVersion: 'R4B')]
class FHIRClaimAccident extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDate|null date When the incident occurred */
        #[NotBlank]
        public ?FHIRDate $date = null,
        /** @var FHIRCodeableConcept|null type The nature of the accident */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRAddress|FHIRReference|null locationX Where the event occurred */
        public FHIRAddress|FHIRReference|null $locationX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
