<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ObservationDefinition.component
 * @description Some observations have multiple component observations, expressed as separate code value pairs.
 */
class FHIRObservationDefinitionComponent extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Type of observation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
		public array $permittedDataType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding> permittedUnit Unit for quantitative results */
		public array $permittedUnit = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRObservationDefinitionQualifiedValue> qualifiedValue Set of qualified values for observation results */
		public array $qualifiedValue = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
