<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Mappings for an individual concept in the source to one or more concepts in the target.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element', fhirVersion: 'R4')]
class FHIRConceptMapGroupElement extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Identifies element being mapped */
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display Display for the code */
        public FHIRString|string|null $display = null,
        /** @var array<FHIRConceptMapGroupElementTarget> target Concept in target system for element */
        public array $target = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
