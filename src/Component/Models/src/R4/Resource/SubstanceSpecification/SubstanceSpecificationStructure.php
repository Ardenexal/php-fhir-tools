<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Structural information.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.structure', fhirVersion: 'R4')]
class SubstanceSpecificationStructure extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null stereochemistry Stereochemistry type */
        public ?CodeableConcept $stereochemistry = null,
        /** @var CodeableConcept|null opticalActivity Optical activity type */
        public ?CodeableConcept $opticalActivity = null,
        /** @var StringPrimitive|string|null molecularFormula Molecular formula */
        public StringPrimitive|string|null $molecularFormula = null,
        /** @var StringPrimitive|string|null molecularFormulaByMoiety Specified per moiety according to the Hill system, i.e. first C, then H, then alphabetical, each moiety separated by a dot */
        public StringPrimitive|string|null $molecularFormulaByMoiety = null,
        /** @var array<SubstanceSpecificationStructureIsotope> isotope Applicable for single substances that contain a radionuclide or a non-natural isotopic ratio */
        public array $isotope = [],
        /** @var SubstanceSpecificationStructureIsotopeMolecularWeight|null molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
        public ?SubstanceSpecificationStructureIsotopeMolecularWeight $molecularWeight = null,
        /** @var array<Reference> source Supporting literature */
        public array $source = [],
        /** @var array<SubstanceSpecificationStructureRepresentation> representation Molecular structural representation */
        public array $representation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
