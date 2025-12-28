<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Links this graph makes rules about.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link', fhirVersion: 'R4')]
class FHIRGraphDefinitionLink extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null path Path in the resource that contains the link */
        public FHIRString|string|null $path = null,
        /** @var FHIRString|string|null sliceName Which slice (if profiled) */
        public FHIRString|string|null $sliceName = null,
        /** @var FHIRInteger|null min Minimum occurrences for this link */
        public ?FHIRInteger $min = null,
        /** @var FHIRString|string|null max Maximum occurrences for this link */
        public FHIRString|string|null $max = null,
        /** @var FHIRString|string|null description Why this link is specified */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRGraphDefinitionLinkTarget> target Potential target for the link */
        public array $target = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
