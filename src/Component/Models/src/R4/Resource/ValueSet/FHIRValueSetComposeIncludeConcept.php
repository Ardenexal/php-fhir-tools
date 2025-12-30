<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specifies a concept to be included or excluded.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include.concept', fhirVersion: 'R4')]
class FHIRValueSetComposeIncludeConcept extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Code or expression from system */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display Text to display for this code for this value set in this valueset */
        public FHIRString|string|null $display = null,
        /** @var array<FHIRValueSetComposeIncludeConceptDesignation> designation Additional representations for this concept */
        public array $designation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
