<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element OperationDefinition.overload
 * @description Defines an appropriate combination of parameters to use when invoking this operation, to help code generators when generating overloaded parameter sets for this operation.
 */
class FHIROperationDefinitionOverload extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> parameterName Name of parameter to include in overload */
		public array $parameterName = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string comment Comments to go on overload */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $comment = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
