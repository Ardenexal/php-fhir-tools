<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @description A group of mappings that all have the same source and target system.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group', fhirVersion: 'R4B')]
class FHIRConceptMapGroup extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null source Source system where concepts to be mapped are defined */
        public ?FHIRUri $source = null,
        /** @var FHIRString|string|null sourceVersion Specific version of the  code system */
        public FHIRString|string|null $sourceVersion = null,
        /** @var FHIRUri|null target Target system that the concepts are to be mapped to */
        public ?FHIRUri $target = null,
        /** @var FHIRString|string|null targetVersion Specific version of the  code system */
        public FHIRString|string|null $targetVersion = null,
        /** @var array<FHIRConceptMapGroupElement> element Mappings for a concept from the source set */
        public array $element = [],
        /** @var FHIRConceptMapGroupUnmapped|null unmapped What to do when there is no mapping for the source concept */
        public ?FHIRConceptMapGroupUnmapped $unmapped = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
