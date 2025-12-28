<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A depiction of the structure or characterization of the substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.structure.representation', fhirVersion: 'R4B')]
class FHIRSubstanceDefinitionStructureRepresentation extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The kind of structural representation (e.g. full, partial) */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null representation The structural representation or characterization as a text string in a standard format */
        public \FHIRString|string|null $representation = null,
        /** @var FHIRCodeableConcept|null format The format of the representation e.g. InChI, SMILES, MOLFILE (note: not the physical file format) */
        public ?\FHIRCodeableConcept $format = null,
        /** @var FHIRReference|null document An attachment with the structural representation e.g. a structure graphic or AnIML file */
        public ?\FHIRReference $document = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
