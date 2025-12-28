<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A graphical structure for this SRU.
 */
#[FHIRBackboneElement(
    parentResource: 'SubstancePolymer',
    elementPath: 'SubstancePolymer.repeat.repeatUnit.structuralRepresentation',
    fhirVersion: 'R5',
)]
class FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The type of structure (e.g. Full, Partial, Representative) */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null representation The structural representation as text string in a standard format e.g. InChI, SMILES, MOLFILE, CDX, SDF, PDB, mmCIF */
        public \FHIRString|string|null $representation = null,
        /** @var FHIRCodeableConcept|null format The format of the representation e.g. InChI, SMILES, MOLFILE, CDX, SDF, PDB, mmCIF */
        public ?\FHIRCodeableConcept $format = null,
        /** @var FHIRAttachment|null attachment An attached file with the structural representation */
        public ?\FHIRAttachment $attachment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
