<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Include one or more codes from a code system or other value set(s).
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include', fhirVersion: 'R4')]
class FHIRValueSetComposeInclude extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null system The system the codes come from */
        public ?\FHIRUri $system = null,
        /** @var FHIRString|string|null version Specific version of the code system referred to */
        public \FHIRString|string|null $version = null,
        /** @var array<FHIRValueSetComposeIncludeConcept> concept A concept defined in the system */
        public array $concept = [],
        /** @var array<FHIRValueSetComposeIncludeFilter> filter Select codes/concepts by their properties (including relationships) */
        public array $filter = [],
        /** @var array<FHIRCanonical> valueSet Select the contents included in this value set */
        public array $valueSet = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
