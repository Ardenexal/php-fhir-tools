<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A filter that can be used in a value set compose statement when selecting concepts using a filter.
 */
#[FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.filter', fhirVersion: 'R4')]
class FHIRCodeSystemFilter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Code that identifies the filter */
        #[NotBlank]
        public ?\FHIRCode $code = null,
        /** @var FHIRString|string|null description How or why the filter is used */
        public \FHIRString|string|null $description = null,
        /** @var array<FHIRFilterOperatorType> operator = | is-a | descendent-of | is-not-a | regex | in | not-in | generalizes | exists */
        public array $operator = [],
        /** @var FHIRString|string|null value What to use for the value */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
