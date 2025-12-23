<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element PlanDefinition.action.output
 * @description Defines the outputs of the action, if any.
 */
class FHIRPlanDefinitionActionOutput extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title User-visible title */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataRequirement requirement What data is provided */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataRequirement $requirement = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string relatedData What data is provided */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $relatedData = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
