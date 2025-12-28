<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The product's name, including full name and possibly coded parts.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductDefinition', elementPath: 'MedicinalProductDefinition.name', fhirVersion: 'R5')]
class FHIRMedicinalProductDefinitionName extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null productName The full product name */
        #[NotBlank]
        public \FHIRString|string|null $productName = null,
        /** @var FHIRCodeableConcept|null type Type of product name, such as rINN, BAN, Proprietary, Non-Proprietary */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRMedicinalProductDefinitionNamePart> part Coding words or phrases of the name */
        public array $part = [],
        /** @var array<FHIRMedicinalProductDefinitionNameUsage> usage Country and jurisdiction where the name applies */
        public array $usage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
