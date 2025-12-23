<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Permission.rule.data
 * @description A description or definition of which activities are allowed to be done on the data.
 */
class FHIRPermissionRuleData extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionRuleDataResource> resource Explicit FHIR Resource references */
		public array $resource = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding> security Security tag code on .meta.security */
		public array $security = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod> period Timeframe encompasing data create/update */
		public array $period = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression expression Expression identifying the data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression $expression = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
