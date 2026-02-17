<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description Include one or more codes from a code system or other value set(s).
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include', fhirVersion: 'R4')]
class ValueSetComposeInclude extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null system The system the codes come from */
        public ?UriPrimitive $system = null,
        /** @var StringPrimitive|string|null version Specific version of the code system referred to */
        public StringPrimitive|string|null $version = null,
        /** @var array<ValueSetComposeIncludeConcept> concept A concept defined in the system */
        public array $concept = [],
        /** @var array<ValueSetComposeIncludeFilter> filter Select codes/concepts by their properties (including relationships) */
        public array $filter = [],
        /** @var array<CanonicalPrimitive> valueSet Select the contents included in this value set */
        public array $valueSet = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
