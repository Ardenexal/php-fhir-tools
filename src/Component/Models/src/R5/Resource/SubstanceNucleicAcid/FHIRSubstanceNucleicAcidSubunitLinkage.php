<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The linkages between sugar residues will also be captured.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceNucleicAcid', elementPath: 'SubstanceNucleicAcid.subunit.linkage', fhirVersion: 'R5')]
class FHIRSubstanceNucleicAcidSubunitLinkage extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null connectivity The entity that links the sugar residues together should also be captured for nearly all naturally occurring nucleic acid the linkage is a phosphate group. For many synthetic oligonucleotides phosphorothioate linkages are often seen. Linkage connectivity is assumed to be 3’-5’. If the linkage is either 3’-3’ or 5’-5’ this should be specified */
        public \FHIRString|string|null $connectivity = null,
        /** @var FHIRIdentifier|null identifier Each linkage will be registered as a fragment and have an ID */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null name Each linkage will be registered as a fragment and have at least one name. A single name shall be assigned to each linkage */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null residueSite Residues shall be captured as described in 5.3.6.8.3 */
        public \FHIRString|string|null $residueSite = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
