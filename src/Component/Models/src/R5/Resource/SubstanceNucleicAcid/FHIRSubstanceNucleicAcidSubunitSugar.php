<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description 5.3.6.8.1 Sugar ID (Mandatory).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceNucleicAcid', elementPath: 'SubstanceNucleicAcid.subunit.sugar', fhirVersion: 'R5')]
class FHIRSubstanceNucleicAcidSubunitSugar extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier The Substance ID of the sugar or sugar-like component that make up the nucleotide */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null name The name of the sugar or sugar-like component that make up the nucleotide */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null residueSite The residues that contain a given sugar will be captured. The order of given residues will be captured in the 5‘-3‘direction consistent with the base sequences listed above */
        public FHIRString|string|null $residueSite = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
