<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Expression
 * @description A expression that is evaluated in a specified context and returns a value. The context of use of the expression must specify the context in which the expression is evaluated, and how the result of the expression is used.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Expression', fhirVersion: 'R4B')]
class FHIRExpression extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string description Natural language description of the condition */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId name Short name assigned to expression for reuse */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId $name = null,
		/** @var null|string language text/cql | text/fhirpath | application/x-fhir-query | text/cql-identifier | text/cql-expression | etc. */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string expression Expression in specified language */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri reference Where the expression is found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri $reference = null,
	) {
		parent::__construct($id, $extension);
	}
}
