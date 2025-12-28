<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Variable is set based either on element value in response body or on header field value in the response headers.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.variable', fhirVersion: 'R4')]
class FHIRTestScriptVariable extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Descriptive name for this variable */
        #[NotBlank]
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null defaultValue Default, hard-coded, or user-defined value for this variable */
        public \FHIRString|string|null $defaultValue = null,
        /** @var FHIRString|string|null description Natural language description of the variable */
        public \FHIRString|string|null $description = null,
        /** @var FHIRString|string|null expression The FHIRPath expression against the fixture body */
        public \FHIRString|string|null $expression = null,
        /** @var FHIRString|string|null headerField HTTP header field name for source */
        public \FHIRString|string|null $headerField = null,
        /** @var FHIRString|string|null hint Hint help text for default value to enter */
        public \FHIRString|string|null $hint = null,
        /** @var FHIRString|string|null path XPath or JSONPath against the fixture body */
        public \FHIRString|string|null $path = null,
        /** @var FHIRId|null sourceId Fixture Id of source expression or headerField within this variable */
        public ?\FHIRId $sourceId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
