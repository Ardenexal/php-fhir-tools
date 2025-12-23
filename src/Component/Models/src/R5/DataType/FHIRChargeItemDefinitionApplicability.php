<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ChargeItemDefinition.applicability
 * @description Expressions that describe applicability criteria for the billing code.
 */
class FHIRChargeItemDefinitionApplicability extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression condition Boolean-valued expression */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression $condition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod effectivePeriod When the charge item definition is expected to be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $effectivePeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifact relatedArtifact Reference to / quotation of the external source of the group of properties */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifact $relatedArtifact = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
