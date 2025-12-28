<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;

/**
 * @description A set of criteria that define the contents of the value set by including or excluding codes selected from the specified code system(s) that the value set draws from. This is also known as the Content Logical Definition (CLD).
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose', fhirVersion: 'R4B')]
class FHIRValueSetCompose extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDate|null lockedDate Fixed date for references with no specified version (transitive) */
        public ?FHIRDate $lockedDate = null,
        /** @var FHIRBoolean|null inactive Whether inactive codes are in the value set */
        public ?FHIRBoolean $inactive = null,
        /** @var array<FHIRValueSetComposeInclude> include Include one or more codes from a code system or other value set(s) */
        public array $include = [],
        /** @var array<FHIRValueSetComposeInclude> exclude Explicitly exclude codes from a code system or other value sets */
        public array $exclude = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
