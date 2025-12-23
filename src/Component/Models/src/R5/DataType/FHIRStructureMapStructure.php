<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element StructureMap.structure
 * @description A structure definition used by this map. The structure definition may describe instances that are converted, or the instances that are produced.
 */
class FHIRStructureMapStructure extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical url Canonical reference to structure definition */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapModelModeType mode source | queried | target | produced */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapModelModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string alias Name for type in this map */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $alias = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string documentation Documentation on use of structure */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $documentation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
