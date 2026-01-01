<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Parameters to the transform.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.target.parameter', fhirVersion: 'R4')]
class FHIRStructureMapGroupRuleTargetParameter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal valueX Parameter value - variable or literal */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
