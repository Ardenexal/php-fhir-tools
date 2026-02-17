<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Variable is set based either on element value in response body or on header field value in the response headers.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.variable', fhirVersion: 'R4')]
class TestScriptVariable extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Descriptive name for this variable */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null defaultValue Default, hard-coded, or user-defined value for this variable */
        public StringPrimitive|string|null $defaultValue = null,
        /** @var StringPrimitive|string|null description Natural language description of the variable */
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null expression The FHIRPath expression against the fixture body */
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null headerField HTTP header field name for source */
        public StringPrimitive|string|null $headerField = null,
        /** @var StringPrimitive|string|null hint Hint help text for default value to enter */
        public StringPrimitive|string|null $hint = null,
        /** @var StringPrimitive|string|null path XPath or JSONPath against the fixture body */
        public StringPrimitive|string|null $path = null,
        /** @var IdPrimitive|null sourceId Fixture Id of source expression or headerField within this variable */
        public ?IdPrimitive $sourceId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
