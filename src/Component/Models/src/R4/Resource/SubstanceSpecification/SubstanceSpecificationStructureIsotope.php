<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;

/**
 * @description Applicable for single substances that contain a radionuclide or a non-natural isotopic ratio.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.structure.isotope', fhirVersion: 'R4')]
class SubstanceSpecificationStructureIsotope extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Substance identifier for each non-natural or radioisotope */
        public ?Identifier $identifier = null,
        /** @var CodeableConcept|null name Substance name for each non-natural or radioisotope */
        public ?CodeableConcept $name = null,
        /** @var CodeableConcept|null substitution The type of isotopic substitution present in a single substance */
        public ?CodeableConcept $substitution = null,
        /** @var Quantity|null halfLife Half life - for a non-natural nuclide */
        public ?Quantity $halfLife = null,
        /** @var SubstanceSpecificationStructureIsotopeMolecularWeight|null molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
        public ?SubstanceSpecificationStructureIsotopeMolecularWeight $molecularWeight = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
