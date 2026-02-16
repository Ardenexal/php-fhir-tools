<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description The codes that are contained in the value set expansion.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion.contains', fhirVersion: 'R4')]
class ValueSetExpansionContains extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null system System value for the code */
        public ?UriPrimitive $system = null,
        /** @var bool|null abstract If user cannot select this entry */
        public ?bool $abstract = null,
        /** @var bool|null inactive If concept is inactive in the code system */
        public ?bool $inactive = null,
        /** @var StringPrimitive|string|null version Version in which this code/display is defined */
        public StringPrimitive|string|null $version = null,
        /** @var CodePrimitive|null code Code - if blank, this is not a selectable code */
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display User display for the concept */
        public StringPrimitive|string|null $display = null,
        /** @var array<ValueSetComposeIncludeConceptDesignation> designation Additional representations for this item */
        public array $designation = [],
        /** @var array<ValueSetExpansionContains> contains Codes contained under this entry */
        public array $contains = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
