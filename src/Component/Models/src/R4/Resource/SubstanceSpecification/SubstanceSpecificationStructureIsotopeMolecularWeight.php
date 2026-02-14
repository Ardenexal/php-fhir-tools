<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;

/**
 * @description The molecular weight or weight range (for proteins, polymers or nucleic acids).
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceSpecification',
    elementPath: 'SubstanceSpecification.structure.isotope.molecularWeight',
    fhirVersion: 'R4',
)]
class SubstanceSpecificationStructureIsotopeMolecularWeight extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null method The method by which the molecular weight was determined */
        public ?CodeableConcept $method = null,
        /** @var CodeableConcept|null type Type of molecular weight such as exact, average (also known as. number average), weight average */
        public ?CodeableConcept $type = null,
        /** @var Quantity|null amount Used to capture quantitative values for a variety of elements. If only limits are given, the arithmetic mean would be the average. If only a single definite value for a given element is given, it would be captured in this field */
        public ?Quantity $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
