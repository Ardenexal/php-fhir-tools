<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Permission.rule
 * @description A set of rules.
 */
class FHIRPermissionRule extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentProvisionTypeType type deny | permit */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentProvisionTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionRuleData> data The selection criteria to identify data that is within scope of this provision */
		public array $data = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionRuleActivity> activity A description or definition of which activities are allowed to be done on the data */
		public array $activity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> limit What limits apply to the use of the data */
		public array $limit = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
