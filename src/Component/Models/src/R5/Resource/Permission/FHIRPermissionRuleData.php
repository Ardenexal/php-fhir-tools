<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A description or definition of which activities are allowed to be done on the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Permission', elementPath: 'Permission.rule.data', fhirVersion: 'R5')]
class FHIRPermissionRuleData extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionRuleDataResource> resource Explicit FHIR Resource references */
		public array $resource = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding> security Security tag code on .meta.security */
		public array $security = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod> period Timeframe encompasing data create/update */
		public array $period = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression expression Expression identifying the data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression $expression = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
