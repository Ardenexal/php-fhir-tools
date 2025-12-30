<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @description The data type or resource that the value of this element is permitted to be.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ElementDefinition.type', fhirVersion: 'R4')]
class FHIRElementDefinitionType extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri code Data type or Resource (reference to definition) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical> profile Profiles (StructureDefinition or IG) - one must apply */
		public array $profile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical> targetProfile Profile (StructureDefinition or IG) on the Reference/canonical target - one must apply */
		public array $targetProfile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAggregationModeType> aggregation contained | referenced | bundled - how aggregated */
		public array $aggregation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReferenceVersionRulesType versioning either | independent | specific */
		public ?FHIRReferenceVersionRulesType $versioning = null,
	) {
		parent::__construct($id, $extension);
	}
}
