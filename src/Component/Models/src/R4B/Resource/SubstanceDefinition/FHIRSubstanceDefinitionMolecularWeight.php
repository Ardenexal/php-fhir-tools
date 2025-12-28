<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The molecular weight or weight range (for proteins, polymers or nucleic acids).
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.molecularWeight', fhirVersion: 'R4B')]
class FHIRSubstanceDefinitionMolecularWeight extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null method The method by which the weight was determined */
        public ?\FHIRCodeableConcept $method = null,
        /** @var FHIRCodeableConcept|null type Type of molecular weight e.g. exact, average, weight average */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null amount Used to capture quantitative values for a variety of elements */
        #[NotBlank]
        public ?\FHIRQuantity $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
