<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcome;

/**
 * @description An error, warning, or information message that results from a system action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'OperationOutcome', elementPath: 'OperationOutcome.issue', fhirVersion: 'R4')]
class OperationOutcomeIssue extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueSeverityType severity fatal | error | warning | information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueSeverityType $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueTypeType code Error or warning code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept details Additional details about the error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $details = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string diagnostics Additional diagnostic information about the issue */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $diagnostics = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> location Deprecated: Path of element(s) related to issue */
		public array $location = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> expression FHIRPath of element(s) related to issue */
		public array $expression = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
