<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceNucleicAcid;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description 5.3.6.8.1 Sugar ID (Mandatory).
 */
#[FHIRBackboneElement(parentResource: 'SubstanceNucleicAcid', elementPath: 'SubstanceNucleicAcid.subunit.sugar', fhirVersion: 'R4')]
class SubstanceNucleicAcidSubunitSugar extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier The Substance ID of the sugar or sugar-like component that make up the nucleotide */
        public ?Identifier $identifier = null,
        /** @var StringPrimitive|string|null name The name of the sugar or sugar-like component that make up the nucleotide */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null residueSite The residues that contain a given sugar will be captured. The order of given residues will be captured in the 5‘-3‘direction consistent with the base sequences listed above */
        public StringPrimitive|string|null $residueSite = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
