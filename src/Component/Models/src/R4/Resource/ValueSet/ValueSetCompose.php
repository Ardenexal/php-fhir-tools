<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;

/**
 * @description A set of criteria that define the contents of the value set by including or excluding codes selected from the specified code system(s) that the value set draws from. This is also known as the Content Logical Definition (CLD).
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose', fhirVersion: 'R4')]
class ValueSetCompose extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var DatePrimitive|null lockedDate Fixed date for references with no specified version (transitive) */
        public ?DatePrimitive $lockedDate = null,
        /** @var bool|null inactive Whether inactive codes are in the value set */
        public ?bool $inactive = null,
        /** @var array<ValueSetComposeInclude> include Include one or more codes from a code system or other value set(s) */
        public array $include = [],
        /** @var array<ValueSetComposeInclude> exclude Explicitly exclude codes from a code system or other value sets */
        public array $exclude = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
