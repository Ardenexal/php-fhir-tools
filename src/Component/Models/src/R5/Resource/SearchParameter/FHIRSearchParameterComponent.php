<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Used to define the parts of a composite search parameter.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SearchParameter', elementPath: 'SearchParameter.component', fhirVersion: 'R5')]
class FHIRSearchParameterComponent extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCanonical|null definition Defines how the part works */
        #[NotBlank]
        public ?FHIRCanonical $definition = null,
        /** @var FHIRString|string|null expression Subexpression relative to main expression */
        #[NotBlank]
        public FHIRString|string|null $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
