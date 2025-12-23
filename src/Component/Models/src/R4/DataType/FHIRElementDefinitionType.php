<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-complex-type ElementDefinition.type
 * @description The data type or resource that the value of this element is permitted to be.
 */
class FHIRElementDefinitionType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri code Data type or Resource (reference to definition) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical> profile Profiles (StructureDefinition or IG) - one must apply */
		public array $profile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical> targetProfile Profile (StructureDefinition or IG) on the Reference/canonical target - one must apply */
		public array $targetProfile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAggregationModeType> aggregation contained | referenced | bundled - how aggregated */
		public array $aggregation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReferenceVersionRulesType versioning either | independent | specific */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReferenceVersionRulesType $versioning = null,
	) {
		parent::__construct($id, $extension);
	}
}
