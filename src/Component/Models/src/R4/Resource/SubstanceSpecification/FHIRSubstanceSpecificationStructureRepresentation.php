<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Molecular structural representation.
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceSpecification',
    elementPath: 'SubstanceSpecification.structure.representation',
    fhirVersion: 'R4',
)]
class FHIRSubstanceSpecificationStructureRepresentation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The type of structure (e.g. Full, Partial, Representative) */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null representation The structural representation as text string in a format e.g. InChI, SMILES, MOLFILE, CDX */
        public FHIRString|string|null $representation = null,
        /** @var FHIRAttachment|null attachment An attached file with the structural representation */
        public ?FHIRAttachment $attachment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
