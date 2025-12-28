<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Some observations have multiple component observations.  These component observations are expressed as separate code value pairs that share the same attributes.  Examples include systolic and diastolic component observations for blood pressure measurement and multiple component observations for genetics observations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.component', fhirVersion: 'R4')]
class FHIRObservationComponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept code Type of component observation (code / type) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSampledData|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod valueX Actual component result */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSampledData|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept dataAbsentReason Why the component result is missing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $dataAbsentReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> interpretation High, low, normal, etc. */
		public array $interpretation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRObservationReferenceRange> referenceRange Provides guide for interpretation of component result */
		public array $referenceRange = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
