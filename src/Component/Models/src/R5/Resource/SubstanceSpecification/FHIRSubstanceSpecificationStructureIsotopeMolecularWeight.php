<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;

/**
 * @description The molecular weight or weight range (for proteins, polymers or nucleic acids).
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceSpecification',
    elementPath: 'SubstanceSpecification.structure.isotope.molecularWeight',
    fhirVersion: 'R5',
)]
class FHIRSubstanceSpecificationStructureIsotopeMolecularWeight extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null method The method by which the molecular weight was determined */
        public ?FHIRCodeableConcept $method = null,
        /** @var FHIRCodeableConcept|null type Type of molecular weight such as exact, average (also known as. number average), weight average */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null amount Used to capture quantitative values for a variety of elements. If only limits are given, the arithmetic mean would be the average. If only a single definite value for a given element is given, it would be captured in this field */
        public ?FHIRQuantity $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
