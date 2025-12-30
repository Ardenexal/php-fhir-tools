<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;

/**
 * @description Applicable for single substances that contain a radionuclide or a non-natural isotopic ratio.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.structure.isotope', fhirVersion: 'R5')]
class FHIRSubstanceSpecificationStructureIsotope extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Substance identifier for each non-natural or radioisotope */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null name Substance name for each non-natural or radioisotope */
        public ?FHIRCodeableConcept $name = null,
        /** @var FHIRCodeableConcept|null substitution The type of isotopic substitution present in a single substance */
        public ?FHIRCodeableConcept $substitution = null,
        /** @var FHIRQuantity|null halfLife Half life - for a non-natural nuclide */
        public ?FHIRQuantity $halfLife = null,
        /** @var FHIRSubstanceSpecificationStructureIsotopeMolecularWeight|null molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
        public ?FHIRSubstanceSpecificationStructureIsotopeMolecularWeight $molecularWeight = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
