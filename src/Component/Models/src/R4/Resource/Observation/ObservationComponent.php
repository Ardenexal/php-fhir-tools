<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation;

/**
 * @description Some observations have multiple component observations.  These component observations are expressed as separate code value pairs that share the same attributes.  Examples include systolic and diastolic component observations for blood pressure measurement and multiple component observations for genetics observations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.component', fhirVersion: 'R4')]
class ObservationComponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Type of component observation (code / type) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|bool|int|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period valueX Actual component result */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|bool|int|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept dataAbsentReason Why the component result is missing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $dataAbsentReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> interpretation High, low, normal, etc. */
		public array $interpretation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation\ObservationReferenceRange> referenceRange Provides guide for interpretation of component result */
		public array $referenceRange = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
