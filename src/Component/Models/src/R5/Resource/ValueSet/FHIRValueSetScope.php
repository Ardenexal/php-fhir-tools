<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Description of the semantic space the Value Set Expansion is intended to cover and should further clarify the text in ValueSet.description.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.scope', fhirVersion: 'R5')]
class FHIRValueSetScope extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string inclusionCriteria Criteria describing which concepts or codes should be included and why */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $inclusionCriteria = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string exclusionCriteria Criteria describing which concepts or codes should be excluded and why */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $exclusionCriteria = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
