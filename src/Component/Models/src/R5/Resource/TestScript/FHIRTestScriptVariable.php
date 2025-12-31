<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Variable is set based either on element value in response body or on header field value in the response headers.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.variable', fhirVersion: 'R5')]
class FHIRTestScriptVariable extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Descriptive name for this variable */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string defaultValue Default, hard-coded, or user-defined value for this variable */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $defaultValue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string description Natural language description of the variable */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string expression The FHIRPath expression against the fixture body */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string headerField HTTP header field name for source */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $headerField = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string hint Hint help text for default value to enter */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $hint = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string path XPath or JSONPath against the fixture body */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId sourceId Fixture Id of source expression or headerField within this variable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $sourceId = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
