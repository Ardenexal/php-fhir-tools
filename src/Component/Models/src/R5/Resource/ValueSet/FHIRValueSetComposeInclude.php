<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description Include one or more codes from a code system or other value set(s).
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include', fhirVersion: 'R5')]
class FHIRValueSetComposeInclude extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null system The system the codes come from */
        public ?FHIRUri $system = null,
        /** @var FHIRString|string|null version Specific version of the code system referred to */
        public FHIRString|string|null $version = null,
        /** @var array<FHIRValueSetComposeIncludeConcept> concept A concept defined in the system */
        public array $concept = [],
        /** @var array<FHIRValueSetComposeIncludeFilter> filter Select codes/concepts by their properties (including relationships) */
        public array $filter = [],
        /** @var array<FHIRCanonical> valueSet Select the contents included in this value set */
        public array $valueSet = [],
        /** @var FHIRString|string|null copyright A copyright statement for the specific code system included in the value set */
        public FHIRString|string|null $copyright = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
