<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description What to do when there is no mapping for the source concept. "Unmapped" does not include codes that are unmatched, and the unmapped element is ignored in a code is specified to have equivalence = unmatched.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.unmapped', fhirVersion: 'R4')]
class FHIRConceptMapGroupUnmapped extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRConceptMapGroupUnmappedModeType|null mode provided | fixed | other-map */
        #[NotBlank]
        public ?FHIRConceptMapGroupUnmappedModeType $mode = null,
        /** @var FHIRCode|null code Fixed code when mode = fixed */
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display Display for the code */
        public FHIRString|string|null $display = null,
        /** @var FHIRCanonical|null url canonical reference to an additional ConceptMap to use for mapping if the source concept is unmapped */
        public ?FHIRCanonical $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
