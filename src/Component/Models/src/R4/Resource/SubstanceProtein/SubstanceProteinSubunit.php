<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceProtein;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description This subclause refers to the description of each subunit constituting the SubstanceProtein. A subunit is a linear sequence of amino acids linked through peptide bonds. The Subunit information shall be provided when the finished SubstanceProtein is a complex of multiple sequences; subunits are not used to delineate domains within a single sequence. Subunits are listed in order of decreasing length; sequences of the same length will be ordered by decreasing molecular weight; subunits that have identical sequences will be repeated multiple times.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceProtein', elementPath: 'SubstanceProtein.subunit', fhirVersion: 'R4')]
class SubstanceProteinSubunit extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var int|null subunit Index of primary sequences of amino acids linked through peptide bonds in order of decreasing length. Sequences of the same length will be ordered by molecular weight. Subunits that have identical sequences will be repeated and have sequential subscripts */
        public ?int $subunit = null,
        /** @var StringPrimitive|string|null sequence The sequence information shall be provided enumerating the amino acids from N- to C-terminal end using standard single-letter amino acid codes. Uppercase shall be used for L-amino acids and lowercase for D-amino acids. Transcribed SubstanceProteins will always be described using the translated sequence; for synthetic peptide containing amino acids that are not represented with a single letter code an X should be used within the sequence. The modified amino acids will be distinguished by their position in the sequence */
        public StringPrimitive|string|null $sequence = null,
        /** @var int|null length Length of linear sequences of amino acids contained in the subunit */
        public ?int $length = null,
        /** @var Attachment|null sequenceAttachment The sequence information shall be provided enumerating the amino acids from N- to C-terminal end using standard single-letter amino acid codes. Uppercase shall be used for L-amino acids and lowercase for D-amino acids. Transcribed SubstanceProteins will always be described using the translated sequence; for synthetic peptide containing amino acids that are not represented with a single letter code an X should be used within the sequence. The modified amino acids will be distinguished by their position in the sequence */
        public ?Attachment $sequenceAttachment = null,
        /** @var Identifier|null nTerminalModificationId Unique identifier for molecular fragment modification based on the ISO 11238 Substance ID */
        public ?Identifier $nTerminalModificationId = null,
        /** @var StringPrimitive|string|null nTerminalModification The name of the fragment modified at the N-terminal of the SubstanceProtein shall be specified */
        public StringPrimitive|string|null $nTerminalModification = null,
        /** @var Identifier|null cTerminalModificationId Unique identifier for molecular fragment modification based on the ISO 11238 Substance ID */
        public ?Identifier $cTerminalModificationId = null,
        /** @var StringPrimitive|string|null cTerminalModification The modification at the C-terminal shall be specified */
        public StringPrimitive|string|null $cTerminalModification = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
